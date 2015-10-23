<?php

/**
 * Author : Scholer <scholer_l@live.com>
 * date   : 2015-10-04.
 */
namespace Xiaoler\Blade\Yii;

use Yii;
use yii\web\View;
use yii\base\ViewRenderer as BaseViewRenderer;

use Xiaoler\Blade\Factory;
use Xiaoler\Blade\FileViewFinder;
use Xiaoler\Blade\Engines\CompilerEngine;
use Xiaoler\Blade\Compilers\BladeCompiler;

class ViewRenderer extends BaseViewRenderer
{

    /**
     * The view finder implementation.
     *
     * @var \Xiaoler\Blade\ViewFinderInterface
     */
    protected $finder;

    /**
     * The engine implementation.
     *
     * @var \Xiaoler\Blade\Engines\EngineInterface
     */
    protected $engine;

    /**
     * The Blade compiler instance.
     *
     * @var \Xiaoler\Blade\Compilers\CompilerInterface
     */
    protected $compiler;

    /**
     * the view factory implementation.
     *
     * @var \Xiaoler\Blade\Factory
     */
    protected $blade;

    /**
     * blade complied cache path.
     *
     * @var string
     */
    protected $cachePath = '@runtime/Blade/cache';

    /**
     *  view file path, support yii aliases.
     *
     * @var array
     */
    protected $viewPath = [
        '@app/views',
        '@yii/views',
        '@vendor/yiisoft/yii2-debug/views',
    ];

    /**
     * optional configuration injected by yii2
     *
     * @var array
     */
    public $options = [];

    /**
     * initialize function, called by yii2.
     */
    public function init()
    {
        $cachePath = Yii::getAlias($this->cachePath);
        $viewPath = array_map(function ($alias) {
            return Yii::getAlias($alias);
        }, $this->viewPath);

        $this->finder = new FileViewFinder($viewPath);

        $this->compiler = new BladeCompiler($cachePath);
        $this->engine = new CompilerEngine($this->compiler);

        $this->blade = new Factory($this->engine, $this->finder);
    }

    /**
     * override function render of yii\base\ViewRenderer.
     *
     * @param yii\web\View $view
     * @param string       $file   absolute file path of view
     * @param array        $params params to view file
     *
     * @return string complied templete
     */
    public function render($view, $file, $params)
    {
        foreach ($this->viewPath as $path) {
            if (strncmp($path, '@', 1) === 0) {
                $path = Yii::getAlias($path);
            }
            if (strpos($file, $path) === 0) {
                $file = ltrim(substr($file, strlen($path)), '/');
                break;
            }
        }
        $path = pathinfo($file);
        if (!in_array($path['extension'], $this->finder->getExtensions())) {
            $this->finder->addExtension($path['extension']);
        }

        $file = $path['dirname'] . '/' . $path['filename'];

        $this->blade->share('app', \Yii::$app);
        $this->blade->share('view', $view);

        return $this->blade->make($file, $params)->render();
    }
}
