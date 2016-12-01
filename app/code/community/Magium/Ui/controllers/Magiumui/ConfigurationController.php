<?php


class Magium_Ui_Magiumui_ConfigurationController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_title('System Configuration')->_title('Magium');
        $this->_setActiveMenu('system/magium');
        $this->renderLayout();
    }

    public function sectionAction()
    {
        $this->loadLayout();
        $this->_title('System Configuration')->_title('Magium');
        $this->_setActiveMenu('system/magium');
        $this->renderLayout();
    }

    public function saveAction()
    {
        $section = $this->getRequest()->getParam('section');
        $storeId = $this->getRequest()->getParam('store', 0);
        try {
            $post = $this->getRequest()->getPost();
            if (isset($post['form_key'])) {
                unset($post['form_key']);
            }
            $this->saveConfigurationItems($post, $storeId);
            Mage::getSingleton('adminhtml/session')->addSuccess("Magium configuration has been saved");
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError("Unable to save Magium configuration: " . $e->getMessage());
        }
        $this->_redirect('*/*/section', ['section' => $section, 'store' => $storeId]);
    }

    protected function saveConfigurationItems($items, $storeId)
    {

        $collection = Mage::getModel('magium_ui/configuration')->getCollection();
        /* @var $collection Magium_Ui_Model_Resource_Configuration_Collection */
        $collection->setStoreId($storeId);
        $collection->setNames(array_keys($items));

        $collection->getResource()->beginTransaction();
        foreach ($collection as $item) {
            /* @var $item Magium_Ui_Model_Configuration */
            if ($item->getStoreId() == $storeId) {
                $name = $item->getName();
                $item->setValue($items[$name]);
                $item->setUpdatedAt(Varien_Date::now());
                unset($items[$name]);
                $item->save();
            }
        }

        foreach ($items as $name => $value) {
            $configuration = Mage::getModel('magium_ui/configuration');
            /* @var $configuration Magium_Ui_Model_Configuration */
            $configuration->setStoreId($storeId);
            $configuration->setName($name);
            $configuration->setValue($value);
            $configuration->setUpdatedAt(Varien_Date::now());
            $configuration->setCreatedAt(Varien_Date::now());
            $configuration->save();
        }

        $collection->getResource()->commit();
    }
}
