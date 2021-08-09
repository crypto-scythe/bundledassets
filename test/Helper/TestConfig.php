<?php

declare(strict_types=1);

namespace CryptoScythe\BundledAssets\Test\Helper;

final class TestConfig implements \Iterator
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function toArray(): array
    {
        return $this->config;
    }

    public function current()
    {
        return '';
    }

    public function next()
    {
    }

    public function key()
    {
        return '';
    }

    public function valid()
    {
        return true;
    }

    public function rewind()
    {
    }
}
