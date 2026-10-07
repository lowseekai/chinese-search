<?php

namespace LowSeekAI\ChineseSearch;

use Flarum\Discussion\Search\DiscussionSearcher;
use Flarum\Extend;
use Flarum\Search\Database\DatabaseSearchDriver;

return [
    (new Extend\Settings())
        ->default('search_cjk_mode', true),

    (new Extend\SearchDriver(DatabaseSearchDriver::class))
        ->setFulltext(DiscussionSearcher::class, Search\ChineseFulltextFilter::class),
];
