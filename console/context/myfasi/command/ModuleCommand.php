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

        public function __construct(Options $options, CLI $cli)
        {
            $this->options = $options;
            $this->cli = $cli;
        }

        public function argsAnalyzer()
        {
            $args = $this->options->getArgs();
            $name = $args[0];
            unset($args[0]);

            foreach ($args as $arg){
                if (mb_strlen($arg) === 2 && $arg[0] === '-') {
                	if (in_array(substr($arg,1,1),$this->allowesArgs['options'])) {
                		$this->givenArgs['option'][] = $arg;
                	}else{
                        $this->cli->error('L\'option '.$arg.' n\'est pas reconnue !');
                        break;
                    }
                }elseif ($arg[0] === '-' && $arg[1] === '-'){ //if(strpos($url, "http") === 0)

                    $params = explode('=',$arg);

                    //todo: gerer le cas --param=value1=valu2

                    $key = str_replace('-','',$params[0]);
                    $value = strtolower($params[1]);
                    if (in_array($key,$this->allowesArgs['params'])) {
                    	$this->givenArgs['params'][$key] = $value;
                    }else{
                        $this->cli->error('Le parametre --'.$key.' n\'est pas reconnue !');
                    }

                }else{
                    $this->cli->fatal('Les arguments passés sont erronés');
                }
            }

            return $this->givenArgs;
        }

        private function option(){

        }

        private function params(){

        }

        public function getGivenArgs()
        {
            return $this->givenArgs;
        }



    }