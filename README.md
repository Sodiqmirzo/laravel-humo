
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Humo client for Laravel 9.x applications

[![Latest Version on Packagist](https://img.shields.io/packagist/v/humo/humo-svgate.svg?style=flat-square)](https://packagist.org/packages/humo/humo-svgate)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/humo/humo-svgate/run-tests?label=tests)](https://github.com/humo/humo-svgate/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/humo/humo-svgate/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/humo/humo-svgate/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/humo/humo-svgate.svg?style=flat-square)](https://packagist.org/packages/humo/humo-svgate)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/humo-svgate.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/humo-svgate)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require humo/humo-svgate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="humo-svgate-config"
```

This is the contents of the published config file:

```php
return [
'base_urls' => [
        '11210' => 'https://humo.uz/api/v1/', /*payment*/
        '13010' => 'https://humo.uz/api/v1/', /*access gateway*/
        '8443' => 'https://humo.uz/api/v1/', /*issuing*/
        '6677' => 'https://humo.uz/api/v1/', /*card*/
        'json_info' => 'https://humo.uz/api/v1/', /*json info*/
    ],
    'username' => env('HUMO_USERNAME', 'username'),
    'password' => env('HUMO_PASSWORD', 'password'),
    'token' => env('HUMO_TOKEN', 'token'),
    'max_amount_without_passport' => env('HUMO_MAX_AMOUNT_WITHOUT_PASSPORT', 0),
];
```


## Usage

```php
$humo = new Uzbek\Humo();
echo $humo->echoPhrase('Hello, Uzbek!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/Sodiqmirzo/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sodiqmirzo Sattorov](https://github.com/Sodiqmirzo)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
