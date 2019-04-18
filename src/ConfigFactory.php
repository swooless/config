<?php declare(strict_types=1);

namespace Swooless\Config;

use Swooless\Config\Loader\FileLoader;
use Swooless\Config\Loader\LoaderInterface;
use Swooless\Config\Loader\ZKLoader;

class ConfigFactory
{
    private static $cache = [];

    public static function getLoader(string $name): LoaderInterface
    {
        $name = strtolower($name);
        $key = md5($name);

        if (in_array($name, self::$cache)) {
            return self::$cache[$key];
        }

        $loader = null;
        switch ($name) {
            case 'zk':
            case 'zookeeper':
                $loader = new ZKLoader();
                break;
            default:
                $loader = new FileLoader();
        }

        self::$cache[$key] = $loader;
        return $loader;
    }
}