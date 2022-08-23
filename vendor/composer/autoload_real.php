<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit1575697b77fb2ca4205c4660cbdb2a74
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit1575697b77fb2ca4205c4660cbdb2a74', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit1575697b77fb2ca4205c4660cbdb2a74', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit1575697b77fb2ca4205c4660cbdb2a74::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}