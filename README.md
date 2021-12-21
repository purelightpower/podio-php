# Podio PHP client library

This is the non-official PHP Client for interacting with the Podio API forked from the Podio community and maintained by [Purelight Power](https://purelightpower.com). Most parts of the Podio API are covered in this client.

The [Podio Community Documentation](https://podio-community.github.io/podio-php/) covers most of the usage of this library. Some things have been updated since Purelight Power forked the repository.

## Usage

When using [composer](https://getcomposer.org), you have to require this library directly in your `composer.json` file. You also have to directly reference it as a repository:

```json
# ./composer.json
...
"require": {
    "purelightpower/podio-php": "dev-master"
},
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:purelightpower/podio-php.git"
    }
]
...
```

Use in your PHP files:

```php
require __DIR__ . '/vendor/autoload.php';

Podio::setup($client_id, $client_secret);
Podio::authenticate_with_app($app_id, $app_token);
$items = PodioItem::filter($app_id);

print "My app has " . count($items) . " items";
```
