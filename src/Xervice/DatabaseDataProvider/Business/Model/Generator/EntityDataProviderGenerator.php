<?php
declare(strict_types=1);

namespace Xervice\DatabaseDataProvider\Business\Model\Generator;


use Xervice\DataProvider\Business\DataProviderFacade;
use Xervice\DataProvider\Business\Model\Parser\DataProviderParserInterface;

class EntityDataProviderGenerator implements EntityDataProviderGeneratorInterface
{
    /**
     * @var \Xervice\DataProvider\Business\DataProviderFacade
     */
    private $dataProviderFacade;

    /**
     * @var \Xervice\DataProvider\Business\Model\Parser\DataProviderParserInterface
     */
    private $entityParser;

    /**
     * EntityDataProviderGenerator constructor.
     *
     * @param \Xervice\DataProvider\Business\DataProviderFacade $dataProviderFacade
     * @param \Xervice\DataProvider\Business\Model\Parser\DataProviderParserInterface $entityParser
     */
    public function __construct(
        DataProviderFacade $dataProviderFacade,
        DataProviderParserInterface $entityParser
    ) {
        $this->dataProviderFacade = $dataProviderFacade;
        $this->entityParser = $entityParser;
    }

    public function generate(): void
    {
        $this->dataProviderFacade->generateCustomDataProvider(
            $this->entityParser
        );
    }
}