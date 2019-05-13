<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf9dd5cce81e5f3bf33ce69ed157c895d
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'alipay\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'alipay\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf9dd5cce81e5f3bf33ce69ed157c895d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf9dd5cce81e5f3bf33ce69ed157c895d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
