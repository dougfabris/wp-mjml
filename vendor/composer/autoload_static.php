<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit491097e71df306191a53882cc5ed4833
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Inc\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Inc\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit491097e71df306191a53882cc5ed4833::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit491097e71df306191a53882cc5ed4833::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
