<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit527134c12feaa5f83befe1f27ab2b8c8
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Incl\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Incl\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit527134c12feaa5f83befe1f27ab2b8c8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit527134c12feaa5f83befe1f27ab2b8c8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
