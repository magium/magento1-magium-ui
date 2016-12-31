<?php

class Magium_Clairvoyant_Model_Resource_Configuration_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected $_storeId = 0;
    protected $_names = [];
    protected $_class;

    protected function _construct()
    {
        $this->_init('magium_clairvoyant/configuration');
    }

    public function setStoreId($storeId)
    {
        if ($storeId instanceof Mage_Core_Model_Store) {
            $storeId = $storeId->getEntityId();
        }
        $this->_storeId = $storeId;
    }

    public function setForClass($class)
    {
        $this->_class = $class;
    }

    public function setNames(array $names)
    {
        $this->_names = $names;
    }

    protected function _beforeLoad()
    {
        if ($this->_names) {
            $this->addFieldToFilter('main_table.name', ['in' => $this->_names]);
        }
        if ($this->_class) {
            $this->addFieldToFilter('class', $this->_class);
        }
        if ($this->_storeId > 0) {
            /*
             * The sub select is necessary so we can use the store ID, but then use 0 (all stores) as the default.  We
             * need to be able to set the order of the results so we can group it by the store_id with the set store
             * being the preference with 0 as the default.
             */

            $this->addFieldToFilter('store_id', ['in' => [$this->_storeId, 0]]);
            $subSelect = $this->getSelect();
            $subSelect->order('store_id DESC');

            $select = $this->getResource()->getReadConnection()->select();
            $select->from(['result' => $subSelect]);
            $select->group('name');
            $this->_select = $select;
        } else {
            $this->addFieldToFilter('store_id', 0);
        }
        return parent::_beforeLoad();
    }

}
