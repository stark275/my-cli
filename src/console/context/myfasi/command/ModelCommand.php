<?php

namespace Console\Context\Myfasi\Command;


/**
 * Class ModelCommand
 *
 * @package Console\Context\Myfasi\Command
 */
class ModelCommand extends MainCommand {

    /**
     * @var array
     */
    protected /** @noinspection PhpUnusedPrivateFieldInspection */
        $allowesArgs = [
        'options' => [],
        'params'  => ['table']
    ];

    /**
     * @return  void
     */
    public function execute()
    {
        $this->makeEntity();
        $this->makeTable();
      
    }


}