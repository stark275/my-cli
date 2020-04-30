<?php
    namespace Console\Context\Myfasi;

    use Psr\Container\ContainerInterface;
    use splitbrain\phpcli\CLI;
    use splitbrain\phpcli\Options;


    /**
     * Class App
     *
     * @package Console\Context\Myfasi
     */
    class App extends CLI
    {

        /**
         * @const string
         */
        const CONFIG = __DIR__ . '/myfasiConfig.php';

        /**
         * @var \Psr\Container\ContainerInterface
         */
        protected $container;

        /**
         * App constructor.
         *
         * @param \Psr\Container\ContainerInterface $container
         * @param bool                              $autocatch
         */
        public function __construct(ContainerInterface $container, bool $autocatch = true)
        {
            parent::__construct($autocatch);
            $this->container = $container;
        }

        /**
         * Register options and arguments on the given $options object
         *
         * @param Options $options
         * @return void
         */
        protected function setup(Options $options)
        {
            $options->setHelp('Bienvenue dans My-CLI \n Auteur: Jacques Mumbere');

            $options->registerCommand('module', 'Crée un module ex: student');
            $options->registerArgument('name','Nom du module',true,'module');

            $options->registerCommand(
                'model', 'Crée un model ex: student => StudentEntity + StudentTable'
            );

            $options->registerArgument('name','Nom du Model',true,'model');
            $options->registerOption(
                'controller','Crée un controller associé au model','c',false,'model'
            );

        }

        /**
         * Your main program
         *
         * Arguments and options have been parsed when this is run
         *
         * @param Options $options
         * @return void
         */
        protected function main(Options $options)
        {
     
             //Provisoir : Si la commande n'existe pas

            if (!in_array($options->getCmd(),['module','model'])) {

                $cmd = $options->getArgs();
                if (count($cmd) !== 0)
                    $cmd = $cmd[0];
                else
                    $cmd = '';

                $this->warning("Commande inexistante : $cmd");
            }

            $commandName = '';

            if ($options->getCmd() === 'module') {
                $commandName = 'module'; 
            }

            if ($options->getCmd() === 'model') {
                $commandName = 'model';
            }

            $command = $this->commandBuilder($commandName);

            if (!class_exists($command)) {
               exit(0);
            }

            $commandClass = $this->container->get($command);
            
            /** @noinspection PhpUndefinedMethodInspection */
            $validated = $commandClass->argsAnalyzer();

            if ($validated !== false) {
                /** @noinspection PhpUndefinedMethodInspection */
                $commandClass->execute();
            }

            $this->info('end <3 !');
        }

        /**
         * @param string $name
         *
         * @return string
         */
        public function commandBuilder(string $name )
        {
            return 'Console\Context\Myfasi\Command\\'.ucfirst(strtolower($name)).'Command';
        }
    }

