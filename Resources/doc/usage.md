# Usage

## Command

``` bash
$ ./app/console widop:fixtures:load [--env=["..."] [--append] [--em="..."] [--purge-with-truncate]
```

## Overview

In most application, you need different fixtures in your prod, dev & test environments.
Some of these fixtures will be specific to an environment and others shared accross environments.

To solve this issue, the bundle follows a simple rule: **organize your ORM directories by environment**
Take a look at this directory structure:

```
 |-- AcmeUserBundle
 | |-- DataFixtures
 | | |-- ORM
 | | | |-- dev
 | | | | |-- LoadUserData.php
 | | | |-- prod
 | | | | |-- LoadUserData.php
 | | | |-- test
 | | | | |-- LoadUserData.php
 |-- AcmeBlogBundle
 | |-- DataFixtures
 | | |-- ORM
 | | | |-- dev
 | | | | |-- LoadPostData.php
 | | | |-- shared
 | | | | |-- LoadTag.php
 | | | |-- test
 | | | | |-- LoadPostData.php
```

Like explain in the command section, the command allows you to specify an environement with the `--env` option.

Now, if you execute the command `php app/console widop:fixtures:load --env=dev`, only fixtures under
`dev` directories will be loaded (the dev configuration will  be used too).

Additionally, fixtures under the `shared` directory will be loaded for all environments.

## In Real life

In order to explain how to use the bundle, I will take a very simple example : a blog (users, posts & tags).

### Development environment

### Test environment

### Production environment
