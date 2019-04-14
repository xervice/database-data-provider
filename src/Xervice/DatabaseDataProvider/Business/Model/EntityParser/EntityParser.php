<?php
declare(strict_types=1);

namespace Xervice\DatabaseDataProvider\Business\Model\EntityParser;


use Xervice\Database\Business\DatabaseFacade;
use Xervice\DatabaseDataProvider\Business\Model\SchemaReader\SchemaReaderInterface;
use Xervice\DataProvider\Business\Model\Parser\DataProviderParserInterface;

class EntityParser implements DataProviderParserInterface
{
    /**
     * @var \Xervice\Database\Business\DatabaseFacade
     */
    private $databaseFacade;

    /**
     * @var \Xervice\DatabaseDataProvider\Business\Model\SchemaReader\SchemaReaderInterface
     */
    private $schemaReader;

    /**
     * @var \Xervice\DatabaseDataProvider\Business\Model\EntityParser\XmlMergerInterface
     */
    private $xmlMerger;

    /**
     * EntityParser constructor.
     *
     * @param \Xervice\Database\Business\DatabaseFacade $databaseFacade
     * @param \Xervice\DatabaseDataProvider\Business\Model\SchemaReader\SchemaReaderInterface $schemaReader
     * @param \Xervice\DatabaseDataProvider\Business\Model\EntityParser\XmlMergerInterface $xmlMerger
     */
    public function __construct(
        DatabaseFacade $databaseFacade,
        SchemaReaderInterface $schemaReader,
        XmlMergerInterface $xmlMerger
    ) {
        $this->databaseFacade = $databaseFacade;
        $this->schemaReader = $schemaReader;
        $this->xmlMerger = $xmlMerger;
    }


    /**
     * @return array
     */
    public function getDataProvider(): array
    {
        foreach ($this->schemaReader->getSchemaData() as $xmlContent) {
            $this->xmlMerger->addXml($xmlContent);
        }

        return $this->xmlMerger->getData();
    }
}