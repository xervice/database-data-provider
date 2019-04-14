<?php
declare(strict_types=1);

namespace Xervice\DatabaseDataProvider\Communication\Console;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xervice\Console\Business\Model\Command\AbstractCommand;

/**
 * @method \Xervice\DatabaseDataProvider\Business\DatabaseDataProviderFacade getFacade()
 */
class EnityGenerateCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('dataprovider:entity:generate')
            ->setDescription('Generate data provider for all database entities');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this
            ->getFacade()
            ->generateEntityDataProvider();
    }
}