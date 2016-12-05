<?php


class Magium_Ui_Magiumui_ConfigurationController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {

        $collection = Mage::getModel('magium_ui/introspected')->getCollection();
        $count = $collection->getSize();
        if ($count == 0) {
            try {
                $this->processScan('');
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    'Test components have been scanned (this should only happen once automatically)'
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError('Test components could not be scanned: ' . $e->getMessage());
            }
//            $thisUrl = Mage::helper('adminhtml')->getUrl();
            $this->_redirect('*/*/*');
            return;
        }

        if (($store = $this->getRequest()->getParam('store')) != false) {
            $this->_redirect('*/*/base');
            return;
        }

        $this->loadLayout();
        $this->_title('System Configuration')->_title('Magium');
        $this->_setActiveMenu('system/magium');
        $this->renderLayout();

    }

    public function saveGlobalAction()
    {

        try {
            $post = $this->getRequest()->getPost();
            if (isset($post['form_key'])) {
                unset($post['form_key']);
            }

            $this->saveOptions($post, 0);
            Mage::getSingleton('adminhtml/session')->addSuccess('Magium configuration has been saved');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError('Unable to save Magium configuration: ' . $e->getMessage());
        }
        $this->_redirect('*/*/index');
    }

    public function baseAction()
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
            $config = 'magium/configuration/sections/' . $section;
            $class = (string)Mage::getConfig()->getNode($config)->type;
            $this->saveConfigurationItems($class, $post, $storeId);
            Mage::getSingleton('adminhtml/session')->addSuccess('Magium configuration has been saved');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError('Unable to save Magium configuration: ' . $e->getMessage());
        }
        $this->_redirect('*/*/section', ['section' => $section, 'store' => $storeId]);
    }

    public function saveBaseAction()
    {
        $storeId = $this->getRequest()->getParam('store', 0);
        try {
            $post = $this->getRequest()->getPost();
            if (isset($post['form_key'])) {
                unset($post['form_key']);
            }

            $this->saveOptions($post, $storeId);

            Mage::getSingleton('adminhtml/session')->addSuccess('Magium base configuration has been saved');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError('Unable to save Magium base configuration: ' . $e->getMessage());
        }
        $this->_redirect('*/*/base', ['store' => $storeId]);
    }

    protected function saveOptions(array $options, $storeId = 0)
    {
        $collection = Mage::getModel('magium_ui/option')->getCollection();
        /* @var $collection Magium_Ui_Model_Resource_Option_Collection */
        $collection->setStoreId($storeId);
        $collection->setNames(array_keys($options));

        $collection->getResource()->beginTransaction();
        foreach ($collection as $item) {
            /* @var $item Magium_Ui_Model_Option */
            if ($item->getStoreId() == $storeId) {
                $name = $item->getName();
                $item->setValue($options[$name]);
                $item->setUpdatedAt(Varien_Date::now());
                unset($options[$name]);
                $item->save();
            }
        }

        foreach ($options as $name => $value) {
            $configuration = Mage::getModel('magium_ui/option');
            /* @var $configuration Magium_Ui_Model_Option */
            $configuration->setStoreId($storeId);
            $configuration->setName($name);
            $configuration->setValue($value);
            $configuration->setUpdatedAt(Varien_Date::now());
            $configuration->setCreatedAt(Varien_Date::now());
            $configuration->save();
        }

        $collection->getResource()->commit();

    }

    public function scanAction()
    {
        $result = ['success' => true];
        try {
            $paths = Mage::app()->getRequest()->getQuery('paths');
            $this->processScan($paths);
            Mage::getSingleton('adminhtml/session')->addSuccess("Test components have been scanned");

        } catch (Exception $e) {
            $result = ['error' => $e->getMessage()];
            Mage::getSingleton('adminhtml/session')->addError("Test components could not be scanned: " . $e->getMessage());
        }

        $this->getResponse()->clearHeaders()->setHeader(
            'Content-type',
            'application/json'
        );

        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode($result)
        );
    }

    protected function processScan($paths)
    {
        $testCase = Mage::helper('magium_ui')->getNullTestCase();
        $introspector = $testCase->get(Magium\Introspection\Introspector::class);
        /* @var $introspector \Magium\Introspection\Introspector */

        $paths = explode("\n", $paths);
        $base = [
            \Magium\AbstractTestCase::class,
            \Magium\Magento\AbstractMagentoTestCase::class
        ];
        foreach ($base as $class) {
            $reflectionClass = new ReflectionClass($class);
            $paths[] = dirname($reflectionClass->getFileName());
        }
        $paths = array_filter($paths);

        $result = $introspector->introspect($paths);

        $collection = Mage::getModel('magium_ui/introspected')->getCollection();
        /* @var $collection Magium_Ui_Model_Resource_Introspected_Collection */
        $collection->getResource()->beginTransaction();
        foreach ($collection as $introspected) {
            /* @var $introspected Magium_Ui_Model_Introspected */
            if (!isset($result[$introspected->getClass()])) {
                $introspected->delete();
                continue;
            }

            $component = $result[$introspected->getClass()];
            if ($component instanceof \Magium\Introspection\ComponentClass) {
                $introspected->setBaseType($component->getBaseType());
                $introspected->setFunctionalType($component->getFunctionalType());
                $introspected->setHierarchy(serialize($component->getHierarchy()));
                $introspected->setUpdatedAt(Varien_Date::now());
                $introspected->save();
                unset($result[$component->getClass()]);
            }
        }

        foreach ($result as $component) {
            if ($component instanceof \Magium\Introspection\ComponentClass) {
                $c = $component->getClass();
                $introspected = Mage::getModel('magium_ui/introspected');
                $introspected->setClass($component->getClass());
                $introspected->setBaseType($component->getBaseType());
                $introspected->setFunctionalType($component->getFunctionalType());
                $introspected->setHierarchy(serialize($component->getHierarchy()));
                $introspected->setCreatedAt(Varien_Date::now());
                $introspected->setUpdatedAt(Varien_Date::now());
                $introspected->save();
            }
        }


        $collection->getResource()->commit();

    }

    protected function saveConfigurationItems($class, $items, $storeId)
    {

        $collection = Mage::getModel('magium_ui/configuration')->getCollection();
        /* @var $collection Magium_Ui_Model_Resource_Configuration_Collection */
        $collection->setStoreId($storeId);
        $collection->setNames(array_keys($items));
        $collection->setForClass($class);

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
            $configuration->setClass($class);
            $configuration->setValue($value);
            $configuration->setUpdatedAt(Varien_Date::now());
            $configuration->setCreatedAt(Varien_Date::now());
            $configuration->save();
        }

        $collection->getResource()->commit();
    }
}
