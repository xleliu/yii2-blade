# yii2-blade

将 Laravel 的 PHP 模板引擎 Blade 移植到 yii2。

Blade 模板总本身使用 blade.php 作为模板的后缀，但由于 yii2 中使用 `pathinfo($viewFile, PATHINFO_EXTENSION);` 解析文件后缀，所以无法识别，所以移植中使用 `bl` 作为模板文件的后缀。

## 使用方式：

``` php
// 在 web.php 中增加配置
'view' => [
    'class' => 'yii\web\View',
    'defaultExtension' => 'php',
    'renderers' => [
        'bl' => [
            'class' => 'xiaoler\blade\ViewRenderer',
        ],
    ],
],
```

**注意：**

php 文件本身没必要无需使用 blade 解析。如果使用，由于无法将 `$this` 伪变量指向 `yii\web\View` 的实例，所以使用 `$view` 变量替代。
