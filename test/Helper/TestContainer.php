<?php

declare(strict_types=1);

namespace CryptoScythe\BundledAssets\Test\Helper;

use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerInterface;

final class TestContainer implements ContainerInterface
{
    private array $dependencies;

    public function __construct(array $dependencies, ?TestContainer $helperPluginManager = null)
    {
        $this->dependencies = array_merge(
            $helperPluginManager === null
                ? []
                : [
                    HelperPluginManager::class => $helperPluginManager,
                ],
            $dependencies,
        );
    }

    public function get(string $id)
    {
        return $this->dependencies[$id] ?? null;
    }

    public function has(string $id): bool
    {
        return isset($this->dependencies[$id]);
    }

    public function withAdditionalDependencies(array $additionalDependencies): self
    {
        return new self(array_merge($this->dependencies, $additionalDependencies));
    }
}
