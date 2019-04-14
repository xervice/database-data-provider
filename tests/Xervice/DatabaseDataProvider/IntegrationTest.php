<?php namespace XerviceTest\DatabaseDataProvider;

use DataProvider\TestEntityDataProvider;
use DataProvider\TesterEntityDataProvider;
use Xervice\Core\Business\Model\Locator\Dynamic\Business\DynamicBusinessLocator;
use Xervice\Core\Business\Model\Locator\Locator;

/**
 * @method \Xervice\DatabaseDataProvider\Business\DatabaseDataProviderFacade getFacade()
 */
class IntegrationTest extends \Codeception\Test\Unit
{
    use DynamicBusinessLocator;

    protected function _before()
    {
        Locator::getInstance()->database()->facade()->initDatabase();
        Locator::getInstance()->database()->facade()->generateConfig();
        Locator::getInstance()->database()->facade()->buildModel();
    }

    /**
     * @group Xervice
     * @group DatabaseDataProvider
     * @group Integration
     */
    public function testGenerateEntityDataProvider()
    {
        $this->getFacade()->generateEntityDataProvider();

        $this->assertTrue(
            class_exists(TestEntityDataProvider::class)
        );
        $this->assertTrue(
            class_exists(TesterEntityDataProvider::class)
        );

        $test = (new TestEntityDataProvider())
            ->setName('TestEntity')
            ->setId(1);

        $tester = (new TesterEntityDataProvider())
            ->setName('TestTester')
            ->setId(1)
            ->setTestid('1')
            ->setTest($test);

        $convertedArray = $tester->toArray();

        $reconverted = new TesterEntityDataProvider();
        $reconverted->fromArray($convertedArray);

        $this->assertEquals(
            'TestTester',
            $reconverted->getName()
        );

        $this->assertEquals(
            'TestEntity',
            $reconverted->getTest()->getName()
        );
    }
}