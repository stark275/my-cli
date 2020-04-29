<?php
namespace Console\Context\Myfasi\Command;

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class MainCommand{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \splitbrain\phpcli\Options
     */
    protected $options;

    /**
     * @var \splitbrain\phpcli\CLI
     */
    protected $cli;

    /**
     * @var array
     * */
    protected $givenArgs = [];


    protected $allowesArgs = [
        'options' => [],
        'params'  => []
    ];

    public function __construct(Options $options, CLI $cli) //injection from config file
    {
        $this->options = $options;
        $this->cli = $cli;
    }

    protected function makeController()
    {
        return $this->createFile(
            $this->name,
            'controller',
            'templates/controller.php', //injection from config file
            '/app/controllers' //injection from config file
        );

    }

    protected function makeEntity()
    {
        return $this->createFile(
            $this->name,
            'entity',
            'templates/entity.php', //injection from config file
            '/app/Entity' //injection from config file
        );

    }

    protected function makeTable()
    {
        return $this->createFile(
            $this->name,
            'table',
            'templates/table.php', //injection from config file
            '/app/tables' //injection from config file
        );
    }

    protected function makeViewPath()
    {
        //injection from config file
        $path = ROOT. $this->normalizePath('\app/view/') .strtolower($this->name);
        if(!is_dir( $path ))
            mkdir( $path, 0777, true );
    }


    public function normalizePath(string $path):string
    {
        return str_replace('/', DIRECTORY_SEPARATOR,$path);
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

    protected function createFile(string $name,string $type, string $templatePath, string $creationPath)
    {
        $templatePath = dirname(__DIR__).DIRECTORY_SEPARATOR.$this->normalizePath($templatePath);
        $template = file_get_contents($templatePath);
        $givenName = ucfirst(strtolower($this->name)).ucfirst(strtolower($type));

        //todo : Creer le namespace dynamyquement
        $classVar = ucfirst(strtolower($type)).'Name';
        $template = str_replace($classVar,$givenName, $template);
        $path = ROOT.str_replace('/',DIRECTORY_SEPARATOR,$creationPath);

        if( !is_dir( $path ))
            mkdir( $path, 0777, true );

        $fileName = $path.DIRECTORY_SEPARATOR.$givenName.'.php';
        // var_dump($path,$fileName,$template);

        if (file_exists($fileName)) {
            $this->cli->warning("Fichier déjà existant: ".$fileName);
            return false;
        }

        return file_put_contents($fileName, $template);
    }

}


