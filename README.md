# php-dht

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

PHP Distributed Hash Table, Suitable for assisting in finding distributed nodes corresponding to key.

You have to cache it and pass it in the next time you use it.

## Install

Via Composer

``` bash
$ composer require yiranzai/dht
```

## Usage

easy

### init

``` php
$hash = new Yiranzai\Dht\Dht();
$hash->addEntityNode('db_server_one')->addEntityNode('db_server_two');
$dbServer =  $hash->getLocation('key_one');

//or

$dhtOne = new Yiranzai\Dht\Dht([
    'virtualNodeNum' => 3,
    'algo'           => 'sha256',
]);
$dhtOne->addEntityNode('db_server_one');
```

### Reuse it

You have to cache it and pass it in the next time you use it.

```php
$hash = new Yiranzai\Dht\Dht();
$hash->addEntityNode('db_server_one')->addEntityNode('db_server_two');
$dbServer =  $hash->getLocation('key_one');
$cache = $hash->toArray();
// please cache this data
$hash = new Yiranzai\Dht\Dht($cache);
$dbServer =  $hash->getLocation('key_one');
```

### Delete Entity Node

Delete entity node

```php
$hash = new Yiranzai\Dht\Dht();
$hash->deleteEntityNode('db_server_one');
```

### Change algo

default algo is time33

```php
$hash = new Yiranzai\Dht\Dht();
$hash->algo('sha256');

//or

$hash = new Yiranzai\Dht\Dht(['algo' => YOUR_PATH]);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email wuqingdzx@gmail.com instead of using the issue tracker.

## Credits

- [yiranzai][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/yiranzai/dht.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/yiranzai/php-dht/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/yiranzai/php-dht.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/yiranzai/php-dht.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/yiranzai/dht.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/yiranzai/dht
[link-travis]: https://travis-ci.org/yiranzai/php-dht
[link-scrutinizer]: https://scrutinizer-ci.com/g/yiranzai/php-dht/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/yiranzai/php-dht
[link-downloads]: https://packagist.org/packages/yiranzai/dht
[link-author]: https://github.com/yiranzai
[link-contributors]: ../../contributors
