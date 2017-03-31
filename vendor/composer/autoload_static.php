<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita894967a5bf77bbed12246b7090f8ab9
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpAmqpLib\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpAmqpLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-amqplib/php-amqplib/PhpAmqpLib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita894967a5bf77bbed12246b7090f8ab9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita894967a5bf77bbed12246b7090f8ab9::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
