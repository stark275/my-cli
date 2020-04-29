<?php
    /**
     * Created by PhpStorm.
     * User: stark
     * Date: 27/04/2020
     * Time: 00:07
     */

    namespace Console;


    class Boot
    {
        /**
         * @var \Console\string
         */
        protected $context;

        public function __construct(string $context)
        {
            $this->context = $context;
        }

        /**
         * @return mixed
         * @throws \Exception
         */
        public function getContext()
        {
            $className = 'Console\Context\\'.ucfirst($this->context).'\App';
            if (class_exists($className)) {
                return new $className();
            }
            throw new \Exception('The given Command context does not exist');
        }


    }