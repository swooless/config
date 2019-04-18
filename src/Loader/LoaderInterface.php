<?php declare(strict_types=1);

namespace Swooless\Config\Loader;

interface LoaderInterface
{
    public function init(array $config): void;

    public function loadToEnv(): void;

    public function getByArray(): array;
}