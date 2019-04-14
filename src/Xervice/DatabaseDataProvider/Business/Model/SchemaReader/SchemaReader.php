<?php
declare(strict_types=1);

namespace Xervice\DatabaseDataProvider\Business\Model\SchemaReader;


class SchemaReader implements SchemaReaderInterface
{
    /**
     * @var string
     */
    private $schemaLocation;

    /**
     * SchemaReader constructor.
     *
     * @param string $schemaLocation
     */
    public function __construct(string $schemaLocation)
    {
        $this->schemaLocation = $schemaLocation;
    }

    /**
     * @return array
     */
    public function getSchemaData(): array
    {
        $data = [];

        foreach (glob($this->schemaLocation . '/*.schema.xml') as $schemaFile) {
            $data[] = file_get_contents($schemaFile);
        }

        return $data;
    }
}