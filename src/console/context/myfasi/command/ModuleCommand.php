<?php
/*
 * This file is part of the my-cli package.
 *
 * (c) Jacques Mumbere <stark1_5@live.com>
 * 
 * This Project is dedicated to the Fasi's MOSSAD Group 
 * Fom Université Protestante au Congo
 */

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
        $this->dumpAutoload();
        $this->cli->info('Module Crée avec succès');
    }
}