<?php

declare(strict_types=1);

namespace CryptoScythe\BundledAssets\View\Helper;

use CryptoScythe\BundledAssets\ConfigProvider;
use JsonException;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\View\Helper\HeadLink;
use Laminas\View\Helper\HeadScript;
use Laminas\View\HelperPluginManager;
use LogicException;
use Psr\Container\ContainerInterface;

final class BundledAssetsHelperFactory
{
    public function __invoke(ContainerInterface $container): BundledAssetsHelper
    {
        $helperPluginMananger = $container->get(HelperPluginManager::class);

        return new BundledAssetsHelper(
            $helperPluginMananger->get(HeadLink::class),
            $helperPluginMananger->get(HeadScript::class),
            $this->manifest($container),
        );
    }

    private function manifest(ContainerInterface $container): array
    {
        $config = $container->get('config');
        $assetsConfig = $config[ConfigProvider::GLOBAL_CONFIG_KEY_VIEW_HELPER_CONFIG][ConfigProvider::class];
        $manifests = $assetsConfig[ConfigProvider::CONFIG_KEY_MANIFESTS];
        $cacheFile = ($config[ConfigAggregator::ENABLE_CACHE] ?? false)
            ? $assetsConfig[ConfigProvider::CONFIG_KEY_CACHE_PATH]
            : '';

        return $cacheFile === ''
            ? $this->parsedManifests($manifests)
            : $this->cachedManifests($manifests, $cacheFile);
    }

    private function cachedManifests(array $parsedManifests, string $cacheFile): array
    {
        if (is_readable($cacheFile)) {
            return require $cacheFile;
        }

        $parsedManifests = $this->parsedManifests($parsedManifests);
        $exportedManifests = var_export($parsedManifests, true);

        file_put_contents(
            $cacheFile,
            sprintf(
                '<?php declare(strict_types=1); return %s;',
                $exportedManifests
            )
        );

        return $parsedManifests;
    }

    /**
     * @throws JsonException
     */
    private function parsedManifests(array $manifests): array
    {
        $parsedManifests = [];

        foreach ($manifests as $name => $manifestFile) {
            if (empty($manifestFile) || !is_readable($manifestFile)) {
                throw new LogicException('Manifest file path empty or manifest file not readable');
            }

            $parsedManifests[$name] = json_decode(
                file_get_contents($manifestFile),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        }

        return $parsedManifests;
    }
}
