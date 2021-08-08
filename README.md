# crypto_scythe/bundledassets

[![MIT License](https://img.shields.io/apm/l/atomic-design-ui.svg?)](https://github.com/tterb/atomic-design-ui/blob/master/LICENSEs)
[![Run unit tests](https://github.com/crypto-scythe/bundledassets/actions/workflows/run-unit-tests.yaml/badge.svg?branch=master)](https://github.com/crypto-scythe/bundledassets/actions/workflows/run-unit-tests.yaml)

A Laminas view helper for adding JavaScript and CSS assets created by bundlers.

## Usage/Examples

### Laminas MVC

```php
# Add module class to Modules configuration
CryptoScythe\BundledAssets\Module::class,
```

### Laminas Mezzio

```php
# Add config provider class to configuration
CryptoScythe\BundledAssets\ConfigProvider::class,
```

### Configuration

```php
# global.php
use CryptoScythe\BundledAssets\ConfigProvider as BundledAssetsConfigProvider;

BundledAssetsConfigProvider::GLOBAL_CONFIG_KEY_VIEW_HELPER_CONFIG => [
    BundledAssetsConfigProvider::class => [
        BundledAssetsConfigProvider::CONFIG_KEY_CACHE_PATH => 'data/bundled_assets_cache.php', # Path to cache file, mostly production 
            BundledAssetsConfigProvider::CONFIG_KEY_MANIFESTS => [ # Hash map of manifest files
                BundledAssetsConfigProvider::CONFIG_KEY_DEFAULT_MANIFEST => 'data/webpack-assets.json',
                'other_manifest' => 'data/other-assets.json', # [optional] You can use multiple asset manifests
        ],
    ],
],
```

### Usage in layout

```php
$this->bundledAssets('entry_point'); # Uses assets from default
$this->bundledAssets('some_other_entry_point', 'other_manifest'); # Uses additionally defined manifest
```

## FAQ

### What is a manifest file?

A manifest file contains a list of the bundled assets, for example the output of
[assets-webpack-plugin](https://www.npmjs.com/package/assets-webpack-plugin).

It looks something like this:
```php
{
    "main": {
        "js": [
            "some_js_asset.12345.js",
            "some_other_js_asset.54321.js"
        ],
        "css": [
            "some_css_asset.12345.css",
            "another_css_asset.abcdef.css"
        ],
    },
    "another": {
        # even more of the same
    }
}
```

## Author

- Chris Fasel [@crypto-scythe](https://www.github.com/crypto-scythe)

## License

[MIT](https://choosealicense.com/licenses/mit/)
