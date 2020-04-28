<?php

    namespace Console\Context\Myfasi\Command;

    use splitbrain\phpcli\CLI;
    use splitbrain\phpcli\Options;

    class ModuleCommand {

        /**
         * @var \splitbrain\phpcli\Options
         */
        protected $options;

        /**
         * @var array
         * */
        private $givenArgs = [];

        private $allowesArgs = [
            'options' => ['a', 'b'],
            'params'  => ['auth','test']
        ];

        /**
         * @var \splitbrain\phpcli\CLI
         */
        private $cli;

        /**
         * @var string
         */
        private $name;

        public function __construct(Options $options, CLI $cli)
        {
            $this->options = $options;
            $this->cli = $cli;
        }

        public function argsAnalyzer()
        {
            $args = $this->options->getArgs();

            $this->name = $args[0];

            unset($args[0]);

            foreach ($args as $arg){
                if (mb_strlen($arg) === 2 && $arg[0] === '-') {
                	if (in_array(substr($arg,1,1),$this->allowesArgs['options'])) {
                		$this->givenArgs['option'][] = $arg;
                	}else{
                        $this->cli->error('L\'option '.$arg.' n\'est pas reconnue !');
                        return false;
                    }
                }elseif ($arg[0] === '-' && $arg[1] === '-'){ //if(strpos($url, "http") === 0)
                    $params = [];
                    $params = explode('=',$arg);

                    if (count($params ) !== 2) {
                        $this->cli->error('Le paramètre "'.$arg.'" n\'est pas valide');
                        $this->cli->info('Essayez la forme: --param=value');
                       return false;
                    }


                    $key = str_replace('-','',$params[0]);
                    $value = strtolower($params[1]);
                    if (in_array($key,$this->allowesArgs['params'])) {
                    	$this->givenArgs['params'][$key] = $value;
                    }else{
                        $this->cli->error('Le parametre "--'.$key.'"" n\'est pas reconnue !');
                        return false;
                    }

                }else{

                    $this->cli->error('Les arguments passés sont erronés');
                    $this->cli->info(
                        'Essayez la forme: php myfasi module Stdent -a -b --param1=value1 --param2=value2'
                    );
                    return false;
                }
            }

            return $this->givenArgs;
        }

        public function createModule()
        {
            $templatePath = dirname(__DIR__).DIRECTORY_SEPARATOR.$this->normalizePath('templates/controller.php');
            $template = file_get_contents($templatePath);
            $givenName = ucfirst(strtolower($this->name)).'Controller';

            //todo : Creer le namespace dynamyquement
            $template = str_replace('ControllerName',$givenName, $template);

            $path = dirname(__DIR__,4) .str_replace('/',DIRECTORY_SEPARATOR,'/app/controllers/');

            if( !is_dir( $path ))
                mkdir( $path, 0777, true );

            $fileName = $path.$givenName.'.php';

            var_dump(file_put_contents($fileName, $template));
        }



        public function getGivenArgs()
        {
            return $this->givenArgs;
        }

        public function normalizePath(string $path)
        {
            return str_replace('/', DIRECTORY_SEPARATOR,$path);
        }



    }