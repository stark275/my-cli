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
        $this->dumpAutoload();
        $this->cli->info('Model Crée avec succès');
        
      
    }


}