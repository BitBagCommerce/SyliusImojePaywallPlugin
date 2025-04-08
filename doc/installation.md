# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
---
ADDITIONAL
- [Known Issues](#known-issues)
---

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version             |
|---------------|---------------------|
| PHP           | \>=8.1              |
| sylius/sylius | \>=1.12.13 - 1.13.x |
| MySQL         | \>= 5.7             |
| NodeJS        | \>= 14.x            |

## Composer:
```bash
composer require bitbag/imoje-paywall-plugin --with-all-dependencies
```

## Basic configuration:
Add plugin dependencies to your `config/bundles.php` file:

```php
# config/bundles.php

return [
    ...
    BitBag\SyliusImojePlugin\BitBagSyliusImojePlugin::class => ['all' => true],
];
```

Add routing to your `config/routes.yaml` file:
```yaml
# config/routes.yaml

bitbag_sylius_imoje_plugin:
    resource: "@BitBagSyliusImojePlugin/config/routes.yaml"
```

Import plugin configuration in `config/packages/_sylius.yaml` file:
```yaml
    # config/packages/_sylius.yaml

    imports:
    # ...
    - { resource: "@BitBagSyliusImojePlugin/config/config.yaml" }
```

## Known issues
### Translations not displaying correctly
For incorrectly displayed translations, execute the command:
```bash
bin/console cache:clear
```
