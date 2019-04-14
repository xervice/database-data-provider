<?php
declare(strict_types=1);

namespace Xervice\DatabaseDataProvider;


use Xervice\Core\Business\Model\Dependency\DependencyContainerInterface as DependencyContainerInterfaceAlias;
use Xervice\Core\Business\Model\Dependency\Provider\AbstractDependencyProvider;
use Xervice\Core\Business\Model\Dependency\DependencyContainerInterface;

/**
 * @method \Xervice\Core\Locator\Locator getLocator()
 */
class DatabaseDataProviderDependencyProvider extends AbstractDependencyProvider
{
    public const DATABASE_FACADE = 'database.facade';

    public const DATA_PROVIDER_FACADE = 'data.provider.facade';

    /**
     * @param DependencyContainerInterfaceAlias $container
     *
     * @return DependencyContainerInterfaceAlias
     */
    public function handleDependencies(DependencyContainerInterface $container): DependencyContainerInterface
    {
        $container = $this->setDatabaseFacade($container);
        $container = $this->setDataProviderFacade($container);

        return $container;
    }

    /**
     * @param \Xervice\Core\Business\Model\Dependency\DependencyContainerInterface $container
     *
     * @return \Xervice\Core\Business\Model\Dependency\DependencyContainerInterface
     */
    protected function setDatabaseFacade(
        DependencyContainerInterface $container
    ): DependencyContainerInterfaceAlias {
        $container[self::DATABASE_FACADE] = function (DependencyContainerInterface $container) {
            return $container->getLocator()->database()->facade();
        };
        return $container;
    }

    /**
     * @param \Xervice\Core\Business\Model\Dependency\DependencyContainerInterface $container
     *
     * @return \Xervice\Core\Business\Model\Dependency\DependencyContainerInterface
     */
    protected function setDataProviderFacade(
        DependencyContainerInterface $container
    ): DependencyContainerInterfaceAlias {
        $container[self::DATA_PROVIDER_FACADE] = function (DependencyContainerInterface $container) {
            return $container->getLocator()->dataProvider()->facade();
        };
        return $container;
    }
}
