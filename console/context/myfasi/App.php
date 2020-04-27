<?php
    namespace Console\Context\Myfasi;

    use splitbrain\phpcli\CLI;
    use splitbrain\phpcli\Options;


    class App extends CLI
    {

        /**
         * Register options and arguments on the given $options object
         *
         * @param Options $options
         * @return void
         */
        protected function setup(Options $options)
        {
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
            if ($options->getCmd() === 'module') {
                $command = $this->commandBuilder('module');
                var_dump((new $command($options, $this))->argsAnalyzer());

                //var_dump(substr('-a',1,1));
            }

            if ($options->getCmd() === 'model') {
                var_dump($options->getArgs());
                var_dump($options->getOpt());
                var_dump(str_replace('-','','--table=si_table'));

            }

            $this->info('end <3 !');
        }

        public function commandBuilder(string $name )
        {
            return 'Console\Context\Myfasi\Command\\'.ucfirst(strtolower($name)).'Command';
        }
    }

