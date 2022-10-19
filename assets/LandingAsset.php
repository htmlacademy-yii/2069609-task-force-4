<?php

namespace app\assets;

use yii\web\AssetBundle;

    /**
     * Main application asset bundle.
     *
     * @author Qiang Xue <qiang.xue@gmail.com>
     * @since 2.0
     */
class LandingAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/landing.css',
    ];
    public $js = [
        'js/landing.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}