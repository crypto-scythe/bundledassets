<?php

declare(strict_types=1);

namespace CryptoScythe\BundledAssets;

final class Module
{
    public function getConfig(): array
    {
        return ConfigProvider::CONFIG;
    }
}
