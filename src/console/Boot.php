<?php
/*
 * This file is part of the my-cli package.
 *
 * (c) Jacques Mumbere <stark1_5@live.com>
 * 
 * This Project is dedicated to the Fasi's MOSSAD Group 
 * Fom Université Protestante au Congo
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