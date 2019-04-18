<?php declare(strict_types=1);

namespace Swooless\Config\Tests;

use PHPUnit\Framework\TestCase;
use Swooless\Config\ConfigFactory;
use Swooless\Config\Loader\FileLoader;

class FileLoaderTest extends TestCase
{
    public function testLoadFile()
    {
        $load = new FileLoader();
        $file = __DIR__ . "/.env";
        $load->init(['path' => [$file]]);
        $config = $load->getByArray();
        self::assertArrayHasKey('TEST', $config);
    }

    public function testLoadByFactory()
    {
        $load = ConfigFactory::getLoader('file');
        $file = __DIR__ . "/.env";
        $load->init(['path' => [$file]]);

        $config = $load->getByArray();

        self::assertArrayHasKey('TEST', $config);

        $load->loadToEnv();
        $value = getenv('DEMO2');

        self::assertTrue('DFD=dfd' == $value);
    }
}