<?php

namespace Console\Context\Myfasi\Command;


/**
 * Class ModuleCommand
 *
 * @package Console\Context\Myfasi\Command
 */
class ModuleCommand extends MainCommand {

    /**
     * @var array
     */
    protected /** @noinspection PhpUnusedPrivateFieldInspection */
        $allowesArgs = [
        'options' => ['a', 'b'],
        'params'  => ['auth','test']
    ];

    /**
     * @return void
     */
    public function execute()
    {
        $this->makeController();
        $this->makeEntity();
        $this->makeTable();
        $this->makeViewPath();
    }
}