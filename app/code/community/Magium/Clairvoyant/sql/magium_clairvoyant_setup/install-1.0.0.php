<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();
$tableName = $installer->getTable('magium_clairvoyant/configuration');
if (!$installer->getConnection()->isTableExists($tableName)) {

    $table = $installer->getConnection()->newTable($tableName)
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
                'identity' => true
            ), 'ID')
        ->addColumn('class', Varien_Db_Ddl_Table::TYPE_TEXT, '255',
            array(
                'nullable' => false,
                'default' => ''
            ), 'Setting Class')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, '255',
            array(
                'nullable' => false,
                'default' => ''
            ), 'Setting Name')
        ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, '1024',
            array(
                'nullable' => false,
                'default' => ''
            ), 'Setting Value')
        ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'nullable' => false,
                'default' => 0
            ), 'Store ID')
        ->addIndex($installer->getIdxName($tableName, array('name')),
            array('name'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
        )
        ->addForeignKey(
            $installer->getFkName('magium_clairvoyant/configuration', 'store_id', 'core/store', 'store_id'),
            'store_id',
            $installer->getTable('core_store'),
            'store_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment('Magium Configuration');
    $installer->getConnection()->createTable($table);
}

$tableName = $installer->getTable('magium_clairvoyant/introspected');
if (!$installer->getConnection()->isTableExists($tableName)) {

    $table = $installer->getConnection()->newTable($tableName)
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
                'identity' => true
            ), 'ID')
        ->addColumn('class', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
            array(
                'nullable' => false,
            ), 'Class')
        ->addColumn('base_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
            array(
                'nullable' => false,
            ), 'Base Type')
        ->addColumn('functional_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
            array(
                'nullable' => false,
            ), 'Functional Type')
        ->addColumn('hierarchy', Varien_Db_Ddl_Table::TYPE_TEXT, 1024,
            array(
                'nullable' => false,
            ), 'Hierarchy')
        ->addIndex($installer->getIdxName($tableName, array('base_type')),
            array('base_type'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
        )
        ->setComment('Magium Interpolation Result');
    $installer->getConnection()->createTable($table);
}

$tableName = $installer->getTable('magium_clairvoyant/option');
if (!$installer->getConnection()->isTableExists($tableName)) {

    $table = $installer->getConnection()->newTable($tableName)
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
                'identity' => true
            ), 'ID')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
            array(
                'nullable' => false,
            ), 'Name')
        ->addColumn('value', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
            array(
                'nullable' => false,
            ), 'Value')
        ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'nullable' => false,
            ), 'Store id')
        ->addIndex($installer->getIdxName($tableName, array('name')),
            array('name'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
        )
        ->addForeignKey(
            $installer->getFkName('magium_clairvoyant/option', 'store_id', 'core/store', 'store_id'),
            'store_id',
            $installer->getTable('core_store'),
            'store_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment('Magium Configuration');
    $installer->getConnection()->createTable($table);
}


$tableName = $installer->getTable('magium_clairvoyant/test');
if (!$installer->getConnection()->isTableExists($tableName)) {

    $table = $installer->getConnection()->newTable($tableName)
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
                'identity' => true
            ), 'ID')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
            array(
                'nullable' => false,
            ), 'Name')
        ->addColumn('command_open', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
            array(
                'nullable' => false,
            ), 'Open Command')
        ->addColumn('pre_conditions', Varien_Db_Ddl_Table::TYPE_TEXT, 255,
            array(
                'nullable' => false,
            ), 'Value')
        ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'nullable' => false,
            ), 'Store id')
        ->addForeignKey(
            $installer->getFkName('magium_clairvoyant/test', 'store_id', 'core/store', 'store_id'),
            'store_id',
            $installer->getTable('core_store'),
            'store_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment('Magium Tests');
    $installer->getConnection()->createTable($table);
}

$tableName = $installer->getTable('magium_clairvoyant/instruction');
if (!$installer->getConnection()->isTableExists($tableName)) {

    $table = $installer->getConnection()->newTable($tableName)
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
                'identity' => true
            ), 'ID')
        ->addColumn('type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
            array(
                'nullable' => false,
            ), 'Type')
        ->addColumn('class', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
            array(
                'nullable' => false,
            ), 'Class')
        ->addColumn('param', Varien_Db_Ddl_Table::TYPE_TEXT, 1024,
            array(
                'nullable' => false,
            ), 'Param')
        ->addColumn('test_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'nullable' => false,
            ), 'Test id')
        ->addForeignKey(
            $installer->getFkName('magium_clairvoyant/instruction', 'test_id', 'magium_clairvoyant/test', 'entity_id'),
            'test_id',
            $installer->getTable('magium_clairvoyant/test'),
            'entity_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment('Magium Test Intructions');
    $installer->getConnection()->createTable($table);
}

$tableName = $installer->getTable('magium_clairvoyant/event');
if (!$installer->getConnection()->isTableExists($tableName)) {

    $table = $installer->getConnection()->newTable($tableName)
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
                'identity' => true
            ), 'ID')
        ->addColumn('test_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'nullable' => false,
            ), 'Test')
        ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
            array(
                'nullable' => false,
            ), 'Store ID')
        ->addColumn('event', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
            array(
                'nullable' => false,
            ), 'Event')
        ->addForeignKey(
            $installer->getFkName('magium_clairvoyant/event', 'test_id', 'magium_clairvoyant/test', 'entity_id'),
            'test_id',
            $installer->getTable('magium_clairvoyant/test'),
            'entity_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->addForeignKey(
            $installer->getFkName('magium_clairvoyant/event', 'store_id', 'core/store', 'store_id'),
            'store_id',
            $installer->getTable('core_store'),
            'store_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment('Magium Test Event Associations');
    $installer->getConnection()->createTable($table);
}


$installer->endSetup();
