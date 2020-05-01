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
    <?php  

   

   define('ROOT', __DIR__);

   define('DS', DIRECTORY_SEPARATOR);

   require('vendor/autoload.php');

   

   /**

    * Contexte courant

    */

   

    $context = \Console\Context\Myfasi\App::class;

   

   

   /**

    * Construction du conteneur d'injection de dépendances

    */

   $containerBuilder = new \DI\ContainerBuilder();

   $containerBuilder->useAutoWiring(true);

   $containerBuilder->addDefinitions('vendor/starkley/my-cli/config.php'); 

   

   $containerBuilder->addDefinitions($context::CONFIG);

   

   $container = $containerBuilder->build();

   

   /**

    * Chargement de la configuration du contexte, qui correspond à l'architecture utilisée

    * Ex: - myfasi et afia qui ont la meme architecture,

    *     - fasinet,

    *     - grafikart Framework, ...

    */

   

   $commandContext = (new Console\Boot(

       $container,

       $container->get('app.context')

       )

   )->getContext();

   

   $commandContext->run();
    ```
    
    Enfin faites
    
    ```composer dump-autoload -o ```