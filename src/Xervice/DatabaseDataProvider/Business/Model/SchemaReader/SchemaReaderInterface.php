<?php
declare(strict_types=1);

namespace Xervice\DatabaseDataProvider\Business\Model\SchemaReader;

interface SchemaReaderInterface
{
    /**
     * @return array
     */
    public function getSchemaData(): array;
}