<?php declare(strict_types=1);


namespace Swooless\Config\Loader;


use Swooless\Config\Exception\InitException;

class FileLoader implements LoaderInterface
{
    private $filePath = [];

    /**
     * @param array $config
     * @throws InitException
     */
    public function init(array $config): void
    {
        if (isset($config['path']) && is_array($config['path'])) {
            $this->filePath = $config['path'];
        } else {
            throw new InitException('Initialization failed, file path setting error!');
        }
    }

    public function loadToEnv(): void
    {
        $configArray = $this->getByArray();

        foreach ($configArray as $key => $value) {
            $envLine = sprintf('%s=%s', strtoupper($key), $value);
            putenv($envLine);
        }
    }

    public function getByArray(): array
    {
        $config = [];

        foreach ($this->filePath as $file) {
            $fp = fopen($file, "r");

            if (!$fp) {
                error_log("File [$file] failed to open");
                continue;
            }

            while (!feof($fp)) {
                $line = fgets($fp, 1024);

                if (!is_string($line)) {
                    continue;
                }

                $line = trim($line);
                if (empty(trim($line))) {
                    continue;
                }

                $index = strpos($line, '=');

                if (false === $index) {
                    error_log('wrong format:' . $line);
                    continue;
                }

                $key = substr($line, 0, $index);
                $value = substr($line, $index + 1);
                $config[$key] = trim($value);
            }

            fclose($fp);
        }

        return $config;
    }
}