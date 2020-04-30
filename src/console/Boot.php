<?php
    /**
     * Created by PhpStorm.
     * User: stark
     * Date: 27/04/2020
     * Time: 00:07
     */

    namespace Console;


    use Psr\Container\ContainerInterface;

    class Boot
    {
        /**
         * @var \Console\string
         */
        protected $context;
        /**
         * @var \Psr\Container\ContainerInterface
         */
        protected $container;

        public function __construct(ContainerInterface $container,string $context)
        {
            $this->context = $context;
            $this->container = $container;
        }

        /**
         * @return mixed
         * @throws \Exception
         */
        public function getContext()
        {
            $className = 'Console\Context\\'.ucfirst($this->context).'\App';
            if (class_exists($className)) {
                return $this->container->get($className);
            }
            throw new \Exception('The given Command context does not exist');
        }


    }