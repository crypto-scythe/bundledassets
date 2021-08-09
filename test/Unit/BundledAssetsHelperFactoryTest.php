<?php

declare(strict_types=1);

namespace CryptoScythe\BundledAssets\Test\Unit;

use CryptoScythe\BundledAssets\Test\Helper\TestConfig;
use CryptoScythe\BundledAssets\Test\Helper\TestContainerFactory;
use CryptoScythe\BundledAssets\View\Helper\BundledAssetsHelper;
use CryptoScythe\BundledAssets\View\Helper\BundledAssetsHelperFactory;
use Laminas\View\HelperPluginManager;
use LogicException;
use PHPUnit\Framework\TestCase;

final class BundledAssetsHelperFactoryTest extends TestCase
{
    private TestContainerFactory $testContainerFactory;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->testContainerFactory = new TestContainerFactory();
    }

    public function testInvocationWithExistingManifest()
    {
        $testManifestFile = __DIR__ . '/../../test-data/testBasicInvocation_manifest.json';
        file_put_contents($testManifestFile, json_encode(['test' => ['js' => [], 'css' => []]]));

        $container = $this->testContainerFactory->__invoke($testManifestFile);
        $bundledAssetsHelperFactory = new BundledAssetsHelperFactory();
        $bundledAssetsHelper = $bundledAssetsHelperFactory($container);

        $this->assertInstanceOf(BundledAssetsHelper::class, $bundledAssetsHelper);
    }

    public function testInvocationWithExistingManifestAndCacheFileCreation()
    {
        $testManifestFile =
            __DIR__ . '/../../test-data/testInvocationWithExistingManifestAndCacheFileCreation_manifest.json';
        $testCacheFile =
            __DIR__ . '/../../test-data/testInvocationWithExistingManifestAndCacheFileCreation_cachedManifest.php';

        file_put_contents($testManifestFile, json_encode(['test' => ['js' => [], 'css' => []]]));

        $container = $this->testContainerFactory->__invoke($testManifestFile, $testCacheFile, true);
        $bundledAssetsHelperFactory = new BundledAssetsHelperFactory();
        $bundledAssetsHelper = $bundledAssetsHelperFactory($container);

        $this->assertInstanceOf(BundledAssetsHelper::class, $bundledAssetsHelper);
        $this->assertTrue(file_exists($testCacheFile), 'Cache file was not created');
    }

    public function testInvocationWithExistingCachedManifest()
    {
        $testManifestFile = __DIR__ . '/../../test-data/testInvocationWithExistingCachedManifest_manifest.json';
        $testCacheFile = __DIR__ . '/../../test-data/testInvocationWithExistingCachedManifest_cachedManifest.php';
        file_put_contents($testManifestFile, json_encode(['default' => ['test' => ['js' => [], 'css' => []]]]));
        file_put_contents($testCacheFile, "<?php return ['test' => ['js' => 'test.js', 'css' => 'test.css']];");

        $container = $this->testContainerFactory->__invoke($testManifestFile, $testCacheFile, true);
        $bundledAssetsHelperFactory = new BundledAssetsHelperFactory();
        $bundledAssetsHelper = $bundledAssetsHelperFactory($container);

        $this->assertInstanceOf(BundledAssetsHelper::class, $bundledAssetsHelper);
    }

    public function testMissingManifestPath()
    {
        $container = $this->testContainerFactory->__invoke('');
        $bundledAssetsHelperFactory = new BundledAssetsHelperFactory();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Manifest file path empty or manifest file not readable');

        $bundledAssetsHelperFactory($container);
    }

    public function testAvailableViewHelperManager()
    {
        $testManifestFile = __DIR__ . '/../../test-data/testAvailableViewHelperManager_manifest.json';
        file_put_contents($testManifestFile, json_encode(['test' => ['js' => [], 'css' => []]]));

        $basicContainer = $this->testContainerFactory->__invoke($testManifestFile);
        $container = $basicContainer->withAdditionalDependencies(
            [
                'ViewHelperManager' => $basicContainer->get(HelperPluginManager::class),
            ]
        );

        $bundledAssetsHelperFactory = new BundledAssetsHelperFactory();
        $bundledAssetsHelper = $bundledAssetsHelperFactory($container);

        $this->assertInstanceOf(BundledAssetsHelper::class, $bundledAssetsHelper);
    }

    public function testConfigIsObject()
    {
        $testManifestFile = __DIR__ . '/../../test-data/testConfigIsObject_manifest.json';
        file_put_contents($testManifestFile, json_encode(['test' => ['js' => [], 'css' => []]]));

        $basicContainer = $this->testContainerFactory->__invoke($testManifestFile);
        $container = $basicContainer->withAdditionalDependencies(
            [
                'config' => new TestConfig($basicContainer->get('config')),
            ]
        );

        $bundledAssetsHelperFactory = new BundledAssetsHelperFactory();
        $bundledAssetsHelper = $bundledAssetsHelperFactory($container);

        $this->assertInstanceOf(BundledAssetsHelper::class, $bundledAssetsHelper);
    }
}
