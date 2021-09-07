<?php

declare(strict_types=1);

namespace CryptoScythe\BundledAssets\View\Helper;

use CryptoScythe\BundledAssets\ConfigProvider;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\HeadLink;
use Laminas\View\Helper\HeadScript;
use LogicException;

final class BundledAssetsHelper extends AbstractHelper
{
    private HeadLink $headLink;
    private HeadScript $headScript;
    private array $assets;

    public function __construct(HeadLink $headLink, HeadScript $headScript, array $assets)
    {
        $this->headLink = $headLink;
        $this->headScript = $headScript;
        $this->assets = $assets;
    }

    public function __invoke(string $requested, string $manifest = ConfigProvider::CONFIG_KEY_DEFAULT_MANIFEST): void
    {
        if (!isset($this->assets[$manifest][$requested])) {
            throw new LogicException(
                sprintf(
                    'Requested assets for "%s" not found',
                    $requested
                )
            );
        }

        $assets = $this->assets[$manifest][$requested];

        $stylesheets = empty($assets['css'] ?? '')
            ? []
            : (is_string($assets['css'])
                ? [$assets['css']]
                : $assets['css']
            );

        $scripts = empty($assets['js'] ?? '')
            ? []
            : (is_string($assets['js'])
                ? [$assets['js']]
                : $assets['js']
            );

        foreach ($stylesheets as $stylesheet) {
            $this->headLink->appendStylesheet($stylesheet);
        }

        foreach ($scripts as $script) {
            $this->headScript->appendFile($script, null, ['defer' => true]);
        }
    }
}
