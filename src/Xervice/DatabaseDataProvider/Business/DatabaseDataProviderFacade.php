<?php
declare(strict_types=1);

namespace Xervice\DatabaseDataProvider\Business;


use Xervice\Core\Business\Model\Facade\AbstractFacade;

/**
 * @method \Xervice\DatabaseDataProvider\Business\DatabaseDataProviderBusinessFactory getFactory()
 * @method \Xervice\DatabaseDataProvider\DatabaseDataProviderConfig getConfig()
 */
class DatabaseDataProviderFacade extends AbstractFacade
{
    /**
     * Generate data provider for all existing entities
     */
    public function generateEntityDataProvider(): void
    {
        $this
            ->getFactory()
            ->createEntityDataProviderGenerator()
            ->generate();
    }
}