<?php
declare(strict_types=1);

namespace Xervice\DatabaseDataProvider;


use Xervice\Core\Business\Model\Config\AbstractConfig;
use Xervice\Database\DatabaseConfig;

class DatabaseDataProviderConfig extends AbstractConfig
{
    /**
     * @return string
     */
    public function getSchemaLocation(): string
    {
        return $this->get(DatabaseConfig::SCHEMA_TARGET, '');
    }
}