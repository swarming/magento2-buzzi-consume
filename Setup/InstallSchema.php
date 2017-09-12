<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Consume\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Buzzi\Consume\Model\ResourceModel\Delivery as DeliveryResourceModel;
use Buzzi\Consume\Api\Data\DeliveryInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->createConsumeQueueTable($setup);

        $setup->endSetup();
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @return void
     */
    protected function createConsumeQueueTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()->newTable(
            $setup->getTable(DeliveryResourceModel::TABLE_NAME)
        )->addColumn(
            DeliveryInterface::DELIVERY_ID,
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'primary' => true, 'unsigned' => true, 'nullable' => false],
            'Id'
        )->addColumn(
            DeliveryInterface::STORE_ID,
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Store ID'
        )->addColumn(
            DeliveryInterface::EVENT_TYPE,
            Table::TYPE_TEXT,
            50,
            ['nullable' => false],
            'Event Type'
        )->addColumn(
            DeliveryInterface::USE_FILE,
            Table::TYPE_BOOLEAN,
            null,
            ['nullable' => false, 'default' => 0],
            'Whether payload is filename or data'
        )->addColumn(
            DeliveryInterface::PAYLOAD,
            Table::TYPE_TEXT,
            DeliveryInterface::MAX_PAYLOAD_LENGTH,
            ['nullable' => false, 'default' => ''],
            'Payload'
        )->addColumn(
            DeliveryInterface::COUNTER,
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Counter'
        )->addColumn(
            DeliveryInterface::RECEIPT,
            Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Receipt'
        )->addColumn(
            DeliveryInterface::IS_CONFIRMED,
            Table::TYPE_BOOLEAN,
            null,
            ['nullable' => false, 'default' => 0],
            'Status'
        )->addColumn(
            DeliveryInterface::DELIVERY_TIME,
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Delivery Time'
        )->addColumn(
            DeliveryInterface::HANDLE_TIME,
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => true],
            'Handle Time'
        )->addColumn(
            DeliveryInterface::STATUS,
            Table::TYPE_TEXT,
            15,
            ['nullable' => false, 'default' => DeliveryInterface::STATUS_PENDING],
            'Status'
        )->addColumn(
            DeliveryInterface::ERROR_MESSAGE,
            Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Error Message'
        )->addIndex(
            $setup->getIdxName(DeliveryResourceModel::TABLE_NAME, [DeliveryInterface::STORE_ID], AdapterInterface::INDEX_TYPE_INDEX),
            [DeliveryInterface::STORE_ID],
            ['type' => AdapterInterface::INDEX_TYPE_INDEX]
        )->addIndex(
            $setup->getIdxName(DeliveryResourceModel::TABLE_NAME, [DeliveryInterface::EVENT_TYPE], AdapterInterface::INDEX_TYPE_INDEX),
            [DeliveryInterface::EVENT_TYPE],
            ['type' => AdapterInterface::INDEX_TYPE_INDEX]
        )->addIndex(
            $setup->getIdxName(DeliveryResourceModel::TABLE_NAME, [DeliveryInterface::STATUS], AdapterInterface::INDEX_TYPE_INDEX),
            [DeliveryInterface::STATUS],
            ['type' => AdapterInterface::INDEX_TYPE_INDEX]
        )->addIndex(
            $setup->getIdxName(DeliveryResourceModel::TABLE_NAME, [DeliveryInterface::HANDLE_TIME], AdapterInterface::INDEX_TYPE_INDEX),
            [DeliveryInterface::HANDLE_TIME],
            ['type' => AdapterInterface::INDEX_TYPE_INDEX]
        )->setComment(
            'Buzzi Consume Queue'
        );
        $setup->getConnection()->createTable($table);
    }
}
