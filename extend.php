<?php

namespace LowSeekAI\ChineseSearch;

use Flarum\Discussion\Search\DiscussionSearcher;
use Flarum\Extend;
use Flarum\Search\Database\DatabaseSearchDriver;

return [
    (new Extend\SearchDriver(DatabaseSearchDriver::class))
        ->setFulltext(DiscussionSearcher::class, Search\ChineseFulltextFilter::class),
];
