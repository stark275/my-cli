<?php
namespace Console\Context\Myfasi\Command;

use Console\Context\Myfasi\App;
use Psr\Container\ContainerInterface;
use splitbrain\phpcli\Options;

/**
 * Class MainCommand
 *
 * @package Console\Context\Myfasi\Command
 */
abstract class MainCommand{

    /**
     * @var string
     * */
    protected $args;

    /**
     * @var array
     * */
    protected $givenArgs = [];

    /**
     * @var array
     * */
    protected $allowesArgs = [
        'options' => [],
        'params'  => []
    ];

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
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * MainCommand constructor.
     *
     * @param \splitbrain\phpcli\Options        $options
     * @param \Console\Context\Myfasi\App       $cli
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(Options $options, App $cli, ContainerInterface $container)
    {
        $this->options = $options;
        $this->cli = $cli;
        $tempArg = $this->options->getArgs();
        unset($tempArg[0]);
        $this->args =$tempArg;
        $this->container = $container;
    }

    /**
     * @return bool|int
     */
    protected function makeController()
    {
        return $this->createFile(
            $this->name,
            'controller',
            $this->container->get('controllerTemplate'),
            $this->container->get('controllersPath') 
        );

    }

    /**
     * @return bool|int
     */
    protected function makeEntity()
    {
        return $this->createFile(
            $this->name,
            'entity',
            $this->container->get('entityTemplate'),
            $this->container->get('entitiesPath') 
        );

    }

    /**
     * @return bool|int
     */
    protected function makeTable()
    {
        return $this->createFile(
            $this->name,
            'table',
            $this->container->get('tableTemplate'),
            $this->container->get('tablesPath') 
        );
    }

    /**
     *
     */
    protected function makeViewPath()
    {
        $viewPath =  $this->container->get('viewssPath'); 
        $path = ROOT. $this->normalizePath($viewPath) .strtolower($this->name);
        if(!is_dir( $path ))
            mkdir( $path, 0777, true );
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function normalizePath(string $path):string
    {
        return str_replace('/', DIRECTORY_SEPARATOR,$path);
    }

    /**
     * @return array|bool
     */
    public function argsAnalyzer()
    {
        $args = $this->args;
        $this->name = $args[1];

        unset($args[1]);

        if (count($args) !== 0) {
            foreach ($args as $arg) {
                if (mb_strlen($arg) === 2 && $arg[0] === '-') {
                    if (in_array(substr($arg,1,1), $this->allowesArgs['options'])) {
                        $this->givenArgs['option'][] = $arg;
                    } else {
                        $this->cli->error('L\'option ' . $arg . ' n\'est pas reconnue !');
                        return false;
                    }
                } elseif ($arg[0] === '-' && $arg[1] === '-') {
                    $params = [];
                    $params = explode('=',$arg);

                    if (count($params) !== 2) {
                        $this->cli->error('Le paramètre "' . $arg . '" n\'est pas valide');
                        $this->cli->info('Essayez la forme: --param=value');
                        return false;
                    }

                    $key = str_replace('-','', $params[0]);
                    $value = strtolower($params[1]);
                    if (in_array($key,
                        $this->allowesArgs['params'])) {
                        $this->givenArgs['params'][$key] = $value;
                    } else {
                        $this->cli->error('Le parametre "--' . $key . '"" n\'est pas reconnu !');
                        return false;
                    }

                } else {

                    $this->cli->error('Les arguments passés sont erronés');
                    $this->cli->info(
                        'Essayez la forme: php myfasi module Stdent -a -b --param1=value1 --param2=value2'
                    );
                    return false;
                }
            }
        }

        return $this->givenArgs;
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $templatePath
     * @param string $creationPath
     *
     * @return bool|int
     */
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


