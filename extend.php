<?php

namespace LowSeekAI\ChineseSearch;

use Flarum\Discussion\Search\DiscussionSearcher;
use Flarum\Extend;
use Flarum\Frontend\Document;
use Flarum\Search\Database\DatabaseSearchDriver;

return [
    (new Extend\Frontend('forum'))
        ->content(function (Document $document) {
            $document->payload['settings'] = array_merge($document->payload['settings'] ?? [], [
                'search_cjk_mode' => true,
            ]);
        }),

    (new Extend\SearchDriver(DatabaseSearchDriver::class))
        ->setFulltext(DiscussionSearcher::class, Search\ChineseFulltextFilter::class),
];
