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

As explained in the command section, the command allows you to specify an environement with the `--env` option.

If you execute the command `php app/console widop:fixtures:load --env=dev`, only fixtures under
`dev` directories will be loaded using the dev configuration..

**In any case, fixtures under the `shared` directory will always be loaded for all environments.**
