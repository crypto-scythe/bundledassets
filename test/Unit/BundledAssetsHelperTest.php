<?php

declare(strict_types=1);

namespace CryptoScythe\BundledAssets\Test\Unit;

use CryptoScythe\BundledAssets\Test\Helper\TestContainerFactory;
use CryptoScythe\BundledAssets\View\Helper\BundledAssetsHelperFactory;
use Laminas\View\Helper\HeadLink;
use Laminas\View\Helper\HeadScript;
use Laminas\View\Renderer\PhpRenderer;
use LogicException;
use PHPUnit\Framework\TestCase;

final class BundledAssetsHelperTest extends TestCase
{
    private TestContainerFactory $testContainerFactory;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->testContainerFactory = new TestContainerFactory();
    }

    public function testBothAssetTypesSingleItem()
    {
        $testManifestFile = __DIR__ . '/../../test-data/testBothAssetTypesSingleItem_manifest.json';

        file_put_contents(
            $testManifestFile,
            json_encode(
                [
                    'testEntryBundle' => [
                        'js' => 'test.js',
                        'css' => 'test.css',
                    ]
                ]
            )
        );

        $headLink = (new HeadLink())->setView(new PhpRenderer());
        $headScript = new HeadScript();

        $container = $this->testContainerFactory->__invoke(
            $testManifestFile,
            '',
            false,
            $headLink,
            $headScript
        );

        $bundledAssetsHelper = (new BundledAssetsHelperFactory())($container);
        $bundledAssetsHelper->__invoke('testEntryBundle');

        $this->assertSame(
            '<script type="text&#x2F;javascript" defer="defer" src="test.js"></script>',
            (string) $headScript
        );

        $this->assertSame(
            '<link href="test.css" media="screen" rel="stylesheet" type="text&#x2F;css">',
            (string) $headLink
        );
    }

    public function testBothAssetTypesMultipleItems()
    {
        $testManifestFile = __DIR__ . '/../../test-data/testBothAssetTypesMultipleItems_manifest.json';

        file_put_contents(
            $testManifestFile,
            json_encode(
                [
                    'testEntryBundle' => [
                        'js' => [
                            'test1.js',
                            'test2.js',
                        ],
                        'css' => [
                            'test1.css',
                            'test2.css',
                            'test3.css',
                        ],
                    ]
                ]
            )
        );

        $headLink = (new HeadLink())->setView(new PhpRenderer());
        $headScript = new HeadScript();

        $container = $this->testContainerFactory->__invoke(
            $testManifestFile,
            '',
            false,
            $headLink,
            $headScript
        );

        $bundledAssetsHelper = (new BundledAssetsHelperFactory())($container);
        $bundledAssetsHelper->__invoke('testEntryBundle');

        $this->assertSame(
            <<<'EOHTML'
            <script type="text&#x2F;javascript" defer="defer" src="test1.js"></script>
            <script type="text&#x2F;javascript" defer="defer" src="test2.js"></script>
            EOHTML,
            (string) $headScript
        );

        $this->assertSame(
            <<<'EOHTML'
            <link href="test1.css" media="screen" rel="stylesheet" type="text&#x2F;css">
            <link href="test2.css" media="screen" rel="stylesheet" type="text&#x2F;css">
            <link href="test3.css" media="screen" rel="stylesheet" type="text&#x2F;css">
            EOHTML,
            (string) $headLink
        );
    }

    public function testJsAssetTypeMissing()
    {
        $testManifestFile = __DIR__ . '/../../test-data/testJsAssetTypeMissing_manifest.json';

        file_put_contents(
            $testManifestFile,
            json_encode(
                [
                    'testEntryBundle' => [
                        'css' => 'test.css',
                    ]
                ]
            )
        );

        $headLink = (new HeadLink())->setView(new PhpRenderer());
        $headScript = new HeadScript();

        $container = $this->testContainerFactory->__invoke(
            $testManifestFile,
            '',
            false,
            $headLink,
            $headScript
        );

        $bundledAssetsHelper = (new BundledAssetsHelperFactory())($container);
        $bundledAssetsHelper->__invoke('testEntryBundle');

        $this->assertSame(
            '',
            (string) $headScript
        );

        $this->assertSame(
            '<link href="test.css" media="screen" rel="stylesheet" type="text&#x2F;css">',
            (string) $headLink
        );
    }

    public function testCssAssetTypesMissing()
    {
        $testManifestFile = __DIR__ . '/../../test-data/testCssAssetTypesMissing_manifest.json';

        file_put_contents(
            $testManifestFile,
            json_encode(
                [
                    'testEntryBundle' => [
                        'js' => 'test.js',
                    ]
                ]
            )
        );

        $headLink = (new HeadLink())->setView(new PhpRenderer());
        $headScript = new HeadScript();

        $container = $this->testContainerFactory->__invoke(
            $testManifestFile,
            '',
            false,
            $headLink,
            $headScript
        );

        $bundledAssetsHelper = (new BundledAssetsHelperFactory())($container);
        $bundledAssetsHelper->__invoke('testEntryBundle');

        $this->assertSame(
            '<script type="text&#x2F;javascript" defer="defer" src="test.js"></script>',
            (string) $headScript
        );

        $this->assertSame(
            '',
            (string) $headLink
        );
    }

    public function testNonDefaultManifest()
    {
        $testManifestFileDefault = __DIR__ . '/../../test-data/testNonDefaultManifest_manifest_default.json';

        file_put_contents(
            $testManifestFileDefault,
            json_encode(
                [
                    'testEntryBundle' => [
                        'js' => 'test.js',
                        'css' => 'test.css',
                    ]
                ]
            )
        );

        $testManifestFileNonDefault = __DIR__ . '/../../test-data/testNonDefaultManifest_manifest_non_default.json';

        file_put_contents(
            $testManifestFileNonDefault,
            json_encode(
                [
                    'testEntryBundle' => [
                        'js' => 'test_non_default.js',
                        'css' => 'test_non_default.css',
                    ]
                ]
            )
        );

        $headLink = (new HeadLink())->setView(new PhpRenderer());
        $headScript = new HeadScript();

        $container = $this->testContainerFactory->__invoke(
            $testManifestFileDefault,
            '',
            false,
            $headLink,
            $headScript,
            [
                'non_default' => $testManifestFileNonDefault,
            ]
        );

        $bundledAssetsHelper = (new BundledAssetsHelperFactory())($container);
        $bundledAssetsHelper->__invoke('testEntryBundle', 'non_default');

        $this->assertSame(
            '<script type="text&#x2F;javascript" defer="defer" src="test_non_default.js"></script>',
            (string) $headScript
        );

        $this->assertSame(
            '<link href="test_non_default.css" media="screen" rel="stylesheet" type="text&#x2F;css">',
            (string) $headLink
        );
    }

    public function testEntryBundleMissing()
    {
        $testManifestFile = __DIR__ . '/../../test-data/testEntryBundleMissing_manifest.json';

        file_put_contents($testManifestFile, json_encode([]));

        $headLink = (new HeadLink())->setView(new PhpRenderer());
        $headScript = new HeadScript();

        $container = $this->testContainerFactory->__invoke(
            $testManifestFile,
            '',
            false,
            $headLink,
            $headScript
        );

        $bundledAssetsHelper = (new BundledAssetsHelperFactory())($container);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Requested assets for "testEntryBundle" not found');

        $bundledAssetsHelper->__invoke('testEntryBundle');
    }
}
