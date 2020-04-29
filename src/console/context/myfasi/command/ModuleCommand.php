<?php

namespace Console\Context\Myfasi\Command;


class ModuleCommand extends MainCommand {



    protected /** @noinspection PhpUnusedPrivateFieldInspection */
        $allowesArgs = [
        'options' => ['a', 'b'],
        'params'  => ['auth','test']
    ];


    public function createModule()
    {
        $this->makeController();
        $this->makeEntity();
        $this->makeTable();
        $this->makeViewPath();
    }


}