# PHP-CLI

Simple PHP command line interface for personnal project.

Features:

- **Module creation**

## NOTE 

If your are using Windows, please download a linux terminal like `cmder`

## usage

1. Create a file named `myfasi` without a specific extension in the root of your project 
2. Once created, copy the following code in this one
    ```php
    #!/usr/bin/php 
    define('ROOT', __DIR__);
    require('vendor/autoload.php');
    $config = require(__DIR__.'/src/console/config.php');
    $commandContext = (new Console\Boot($config['app.context']))->getContext();
    $commandContext->run();
    ```