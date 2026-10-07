<?php

namespace LowSeekAI\ChineseSearch\Search;

use Flarum\Post\Post;
use Flarum\Search\AbstractFulltextFilter;
use Flarum\Search\Database\DatabaseSearchState;
use Flarum\Search\SearchState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ChineseFulltextFilter extends AbstractFulltextFilter
{
    public function search(SearchState $state, string $value): void
    {
        /** @var DatabaseSearchState $state */
        $query = $state->getQuery();
        $grammar = $query->getGrammar();
        $matchingComments = function (QueryBuilder $subquery) use ($state, $value): QueryBuilder {
            return $subquery
                ->from('posts')
                ->whereColumn('posts.discussion_id', 'discussions.id')
                ->where('posts.type', 'comment')
                ->where('posts.content', 'like', "%{$value}%")
                ->whereIn(
                    'posts.id',
                    Post::whereVisibleTo($state->getActor())->select('posts.id')->toBase()
                );
        };

        $coalesce = 'coalesce(min('.$grammar->wrap('posts.id').'), '.$grammar->wrap('discussions.first_post_id').')';

        $query
            ->selectSub(function (QueryBuilder $subquery) use ($matchingComments, $coalesce) {
                $matchingComments($subquery)->selectRaw($coalesce);
            }, 'most_relevant_post_id')
            ->where(function (Builder $builder) use ($value, $matchingComments) {
                $builder
                    ->where('discussions.title', 'like', "%{$value}%")
                    ->orWhereExists(function (QueryBuilder $subquery) use ($matchingComments) {
                        $matchingComments($subquery)->selectRaw('1');
                    });
            });

        $state->setDefaultSort(fn (Builder $builder) => $builder->orderByDesc('discussions.last_posted_at'));
    }
}
