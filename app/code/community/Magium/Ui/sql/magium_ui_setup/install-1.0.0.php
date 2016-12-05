<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();
$tableName = $installer->getTable('magium_ui/configuration');
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
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
            array(
                'nullable' => false,
            ), 'Created At')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
            array(
                'nullable' => false,
            ), 'Modified At')
        ->addIndex($installer->getIdxName($tableName, array('name')),
            array('name'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
        )
        ->addForeignKey(
            $installer->getFkName('magium_ui/configuration', 'store_id', 'core/store', 'store_id'),
            'store_id',
            $installer->getTable('core_store'),
            'store_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment('Magium Configuration');
    $installer->getConnection()->createTable($table);
}

$tableName = $installer->getTable('magium_ui/introspected');
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
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
            array(
                'nullable' => false,
            ), 'Created At')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
            array(
                'nullable' => false,
            ), 'Modified At')
        ->addIndex($installer->getIdxName($tableName, array('base_type')),
            array('base_type'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
        )
        ->setComment('Magium Interpolation Result');
    $installer->getConnection()->createTable($table);
}

$tableName = $installer->getTable('magium_ui/option');
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
        ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
            array(
                'nullable' => false,
            ), 'Store id')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
            array(
                'nullable' => false,
            ), 'Created At')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null,
            array(
                'nullable' => false,
            ), 'Modified At')
        ->addIndex($installer->getIdxName($tableName, array('name')),
            array('name'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
        )
        ->setComment('Magium Configuration');
    $installer->getConnection()->createTable($table);
}
$installer->endSetup();
