# Installation

## Symfony 2.1.*

### Add the WidopFixturesBundle to your composer configuration

Add the bundle to the require section of your `composer.json`

``` json
{
    "require": {
        "widop/fixtures-bundle": "dev-master"
    }
}
```

Run the composer update command

``` bash
$ php composer.phar update
```

**Note**: you need to add `dev` to the `minimum-stability` section:

``` json
{
    "minimum-stability": "dev"
}
```

### Add the WidopFixturesBundle to your application kernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    return array(
        //..
        new Widop\FixturesBundle\WidopFixturesBundle(),
    );
}
```

## Symfony 2.0.*

### Add the WidopFixturesBundle to your vendor/bundles/ directory

#### Using the vendors script

Add the following lines in your ``deps`` file

```
[WidopFixturesBundle]
    git=http://github.com/widop/WidopFixturesBundle.git
    target=bundles/Widop/FixturesBundle
    version=origin/2.0
```

Run the vendors script

``` bash
$ php bin/vendors update
```

#### Using submodules

``` bash
$ git submodule add http://github.com/widop/WidopFixturesBundle.git vendor/bundles/Widop/FixturesBundle
```

### Add the Widop namespace to your autoloader

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    //..
    'Widop' => __DIR__.'/../vendor/bundles',
);
```

### Add the WidopFixturesBundle to your application kernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    return array(
        //..
        new Widop\FixturesBundle\WidopFixturesBundle(),
    );
}
```
