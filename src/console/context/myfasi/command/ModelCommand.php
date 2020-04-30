<?php

namespace Console\Context\Myfasi\Command;


class ModelCommand extends MainCommand {

    protected /** @noinspection PhpUnusedPrivateFieldInspection */
        $allowesArgs = [
        'options' => [],
        'params'  => ['table']
    ];

    public function execute()
    {
        $this->makeEntity();
        $this->makeTable();
      
    }


}