<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit0dacc0b8cb207d0fd5ac7609b1682cd1
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

        spl_autoload_register(array('ComposerAutoloaderInit0dacc0b8cb207d0fd5ac7609b1682cd1', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit0dacc0b8cb207d0fd5ac7609b1682cd1', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit0dacc0b8cb207d0fd5ac7609b1682cd1::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
