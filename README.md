# yii2-blade

将 Laravel 的 PHP 模板引擎 Blade 移植到 yii2。

## 使用方式：

``` php
// 在 web.php 中增加配置
'view' => [
    'class' => 'yii\web\View',
    'defaultExtension' => 'php',
    'renderers' => [
        'tpl' => [
            'class' => 'Xiaoler\Blade\Yii\ViewRenderer',
        ],
    ],
],
```

**注意：**

php 文件本身没必要无需使用 blade 解析。如果使用，由于无法将 `$this` 伪变量指向 `yii\web\View` 的实例，所以使用 `$view` 变量替代。

引擎将视图文件目录指向 `@app/views`，引用模板文件时请带上文件子目录，如 `site.index`。

Blade 文档：[http://laravel.com/docs/5.1/blade](http://laravel.com/docs/5.1/blade)
