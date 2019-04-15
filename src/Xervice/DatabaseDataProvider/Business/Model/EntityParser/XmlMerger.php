<?php
declare(strict_types=1);


namespace Xervice\DatabaseDataProvider\Business\Model\EntityParser;

use Xervice\DataProvider\Business\Model\DataProvider\AnyDataProvider;
use Xervice\DataProvider\Business\Model\DataProvider\DataProviderInterface;

class XmlMerger implements XmlMergerInterface
{
    /**
     * @var array
     */
    private $mergedXml = [];

    /**
     * @param string $xmlContent
     */
    public function addXml(string $xmlContent): void
    {
        $xml = \simplexml_load_string($xmlContent);

        foreach ($xml->table as $xmlTable) {
            $dataProvider = $this->parseTable($xmlTable);

            $this->addColumns($xmlTable, $dataProvider);
            $this->addForeignKeys($xmlTable, $dataProvider);
        }


    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->mergedXml;
    }

    /**
     * @param \SimpleXMLElement $xmlDataProvider
     *
     * @return string
     */
    private function parseTable(\SimpleXMLElement $xmlTable): string
    {
        $dataProvider = (string)$xmlTable->attributes()['name'] . 'Entity';
        $dataProvider = ucfirst($dataProvider);
        if (!isset($this->mergedXml[$dataProvider])) {
            $this->mergedXml[$dataProvider] = [
                'configs' => [
                    'convertUnderlines' => false,
                    'deprecated' => false
                ],
                'elements' => []
            ];
        }

        return $dataProvider;
    }

    /**
     * @param \SimpleXMLElement $element
     *
     * @return array
     */
    private function getElementData(\SimpleXMLElement $element, array $dataProvider): array
    {
        $type = (string)$element->attributes()['type'];
        $type = $this->convertPropelType($type);

        $required = ((string) $element->attributes()['required']) === 'true';

        $data = [
            'name' => ucfirst((string)$element->attributes()['name']),
            'allownull' => (!$required),
            'default' => (string)$element->attributes()['defaultValue'],
            'type' => $this->getVariableType($type),
            'is_collection' => $this->isCollection($type),
            'is_dataprovider' => $this->isDataProvider($type),
            'isCamelCase' => false
        ];


        if ($data['is_collection']) {
            $data['singleton'] = 'One' . ucfirst($data['name']);
            $data['singleton_type'] = $this->getSingleVariableType($type);
        }

        return $data;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function isDataProvider(string $type): bool
    {
        return (!$this->isSimpleType($type) && !$this->isCollection($type));
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function isCollection(string $type): bool
    {
        return strpos($type, '[]') !== false;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function isSimpleType(string $type): bool
    {
        $validTypes = [
            'int',
            'string',
            'bool',
            'double',
            'float',
            'array',
            'object'
        ];

        return \in_array($type, $validTypes, true);
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getVariableType(string $type): string
    {
        if (!$this->isSimpleType($type)) {
            $type = '\DataProvider\\' . $type . 'DataProvider';
        }

        return $type;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getSingleVariableType(string $type): string
    {
        if (!$this->isSimpleType($type)) {
            $type = '\DataProvider\\' . $type . 'DataProvider';
        }

        return $type;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function convertPropelType(string $type): string
    {
        switch ($type) {
            case 'BOOLEAN':
                $type = 'bool';
                break;
            case 'TINYINT':
            case 'SMALLINT':
            case 'INTEGER':
            case 'REAL':
            case 'DECIMAL':
            case 'BIGINT':
            case 'TIMESTAMP':
                $type = 'int';
                break;
            case 'FLOAT':
            case 'DOUBLE':
                $type = 'float';
                break;
            case 'OBJECT':
                $type = 'object';
                break;
            case 'ARRAY':
                $type = 'array';
                break;
            default:
                $type = 'string';
                break;
        }
        return $type;
    }

    /**
     * @param \SimpleXMLElement $xmlTable
     * @param string $dataProvider
     */
    protected function addColumns(\SimpleXMLElement $xmlTable, string $dataProvider): void
    {
        foreach ($xmlTable->column as $element) {
            $fieldName = (string)$element->attributes()['name'];

            if (isset($this->mergedXml[$dataProvider]['elements'][$fieldName])) {
                $this->mergedXml[$dataProvider]['elements'][$fieldName] = array_merge(
                    $this->mergedXml[$dataProvider]['elements'][$fieldName],
                    $this->getElementData($element, $this->mergedXml[$dataProvider])
                );
            }
            else {
                $this->mergedXml[$dataProvider]['elements'][$fieldName] = $this->getElementData(
                    $element, $this->mergedXml[$dataProvider]
                );
            }
        }
    }

    /**
     * @param \SimpleXMLElement $xmlTable
     * @param string $dataProvider
     */
    protected function addForeignKeys(\SimpleXMLElement $xmlTable, string $dataProvider): void
    {
        $fieldName = 'foreign-key';
        foreach ($xmlTable->{$fieldName} as $element) {
            $fieldName = (string)$element->attributes()['foreignTable'];

            $data = [
                'name' => ucfirst($fieldName),
                'allownull' => true,
                'default' => null,
                'type' => '\DataProvider\\' . ucfirst($fieldName) . 'EntityDataProvider',
                'is_collection' => false,
                'is_dataprovider' => true,
                'isCamelCase' => false
            ];

            if (isset($this->mergedXml[$dataProvider]['elements'][$fieldName])) {
                $this->mergedXml[$dataProvider]['elements'][$fieldName] = array_merge(
                    $this->mergedXml[$dataProvider]['elements'][$fieldName],
                    $data
                );
            }
            else {
                $this->mergedXml[$dataProvider]['elements'][$fieldName] = $data;
            }
        }
    }
}