<?php
declare(strict_types=1);

namespace Xervice\DatabaseDataProvider\Business\Model\Generator;

interface EntityDataProviderGeneratorInterface
{
    public function generate(): void;
}