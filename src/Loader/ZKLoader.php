<?php declare(strict_types=1);


namespace Swooless\Config\Loader;

use Zookeeper;

class ZKLoader implements LoaderInterface
{
    /** @var Zookeeper */
    private $zk;

    /** @var array */
    private $path = [];

    public function init(array $config): void
    {
        $this->zk = new Zookeeper($config['host']);
        $this->path = $config['path'] ?: [];
    }

    public function loadToEnv(): void
    {
        $configArray = $this->pullConfigByZk();

        foreach ($configArray as $key => $value) {
            $envLine = sprintf('%s=%s', strtoupper($key), $value);
            putenv($envLine);
        }
    }

    public function getByArray(): array
    {
        return $this->pullConfigByZk();
    }

    private function pullConfigByZk(): array
    {
        $config = [];

        foreach ($this->path as $path) {
            if ($this->zk->exists($path)) {
                $configList = $this->zk->getChildren($path);
                foreach ($configList as $key) {
                    $value = $this->zk->get($path . '/' . $key);
                    $value && $config[$key] = trim($value);
                }
            } else {
                error_log("Directory [$path] does not exist");
            }
        }

        return $config;
    }
}