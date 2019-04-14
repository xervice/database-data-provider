<?php
declare(strict_types=1);

namespace Xervice\DatabaseDataProvider\Business;


use Xervice\Core\Business\Model\Factory\AbstractBusinessFactory;
use Xervice\Database\Business\DatabaseFacade;
use Xervice\DatabaseDataProvider\Business\Model\EntityParser\EntityParser;
use Xervice\DatabaseDataProvider\Business\Model\EntityParser\XmlMerger;
use Xervice\DatabaseDataProvider\Business\Model\EntityParser\XmlMergerInterface;
use Xervice\DatabaseDataProvider\Business\Model\Generator\EntityDataProviderGenerator;
use Xervice\DatabaseDataProvider\Business\Model\Generator\EntityDataProviderGeneratorInterface;
use Xervice\DatabaseDataProvider\Business\Model\SchemaReader\SchemaReader;
use Xervice\DatabaseDataProvider\Business\Model\SchemaReader\SchemaReaderInterface;
use Xervice\DatabaseDataProvider\DatabaseDataProviderDependencyProvider;
use Xervice\DataProvider\Business\DataProviderFacade;
use Xervice\DataProvider\Business\Model\Parser\DataProviderParserInterface;

/**
 * @method \Xervice\DatabaseDataProvider\DatabaseDataProviderConfig getConfig()
 */
class DatabaseDataProviderBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Xervice\DatabaseDataProvider\Business\Model\Generator\EntityDataProviderGeneratorInterface
     */
    public function createEntityDataProviderGenerator(): EntityDataProviderGeneratorInterface
    {
        return new EntityDataProviderGenerator(
            $this->getDataProviderFacade(),
            $this->createEntityParser()
        );
    }
    
    /**
     * @return \Xervice\DataProvider\Business\Model\Parser\DataProviderParserInterface
     */
    public function createEntityParser(): DataProviderParserInterface
    {
        return new EntityParser(
            $this->getDatabaseFacade(),
            $this->createSchemaReader(),
            $this->createXmlMerger()
        );
    }

    /**
     * @return \Xervice\DatabaseDataProvider\Business\Model\EntityParser\XmlMergerInterface
     */
    public function createXmlMerger(): XmlMergerInterface
    {
        return new XmlMerger();
    }

    /**
     * @return \Xervice\DatabaseDataProvider\Business\Model\SchemaReader\SchemaReaderInterface
     */
    public function createSchemaReader(): SchemaReaderInterface
    {
        return new SchemaReader(
            $this->getConfig()->getSchemaLocation()
        );
    }

    /**
     * @return \Xervice\Database\Business\DatabaseFacade
     */
    public function getDatabaseFacade(): DatabaseFacade
    {
        return $this->getDependency(DatabaseDataProviderDependencyProvider::DATABASE_FACADE);
    }

    /**
     * @return \Xervice\DataProvider\Business\DataProviderFacade
     */
    public function getDataProviderFacade(): DataProviderFacade
    {
        return $this->getDependency(DatabaseDataProviderDependencyProvider::DATA_PROVIDER_FACADE);
    }
}