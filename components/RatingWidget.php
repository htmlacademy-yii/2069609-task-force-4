<?php
namespace app\components;

use yii\base\Widget;

class RatingWidget extends Widget
{
    public $rating;
    const MAX_COUNT_FILL_STARS = 5;
    public function run(): string
    {
        $currentCountFillStars = round($this->rating);
        return str_repeat('<span class="fill-star">&nbsp;</span>', $currentCountFillStars) . str_repeat('<span>&nbsp;</span>', self::MAX_COUNT_FILL_STARS - $currentCountFillStars);
    }
}