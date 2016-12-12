<?php


class Magium_Clairvoyant_Magiumui_ManagementController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_title('System Configuration')->_title('Magium');
        $this->_setActiveMenu('system/magium');
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->loadLayout();
        $this->_title('System Configuration')->_title('Magium')->_title('Edit Magium Test');
        $this->_setActiveMenu('system/magium');

        $test = Mage::getModel('magium_clairvoyant/test')->load($this->getRequest()->getParam('id'));

        $block = $this->getLayout()->getBlock('management_tabs');
        if ($block instanceof Magium_Clairvoyant_Block_Adminhtml_Management_Tabs) {
            $block->setTest($test);
        }

        $block = $this->getLayout()->getBlock('magium_clairvoyant_management_container');
        if ($block instanceof Magium_Clairvoyant_Block_Adminhtml_Management_Container) {
            $block->setTest($test);
        }

        $block = $this->getLayout()->getBlock('magium_clairvoyant_management_builder');
        if ($block instanceof Magium_Clairvoyant_Block_Adminhtml_Management_Builder) {
            $block->setTest($test);
        }

        $this->renderLayout();
    }

    public function saveTestAction()
    {
        $test = Mage::getModel('magium_clairvoyant/test');
        if ($test instanceof Magium_Clairvoyant_Model_Test) {
            if ($this->getRequest()->getParam('id')) {
                $test->load($this->getRequest()->getParam('id'));
            }
            $test->setStoreId($this->getRequest()->getParam('store', 0));
            $test->setName($this->getRequest()->getParam('test_name'));
            $test->setCommandOpen($this->getRequest()->getPost('command_open'));
            $test->setPreConditions($this->getRequest()->getPost('pre_conditions'));
            foreach ($this->getRequest()->getPost('instructions', []) as $instructionId) {
                $instruction = Mage::getModel('magium_clairvoyant/instruction');
                if ($instruction instanceof Magium_Clairvoyant_Model_Instruction) {
                    $type = $this->getRequest()->getPost($instructionId . '_type');
                    $class = $this->getRequest()->getPost($instructionId . '_class');
                    $param = $this->getRequest()->getPost($instructionId . '_param');
                    $instruction->setType($type);
                    $instruction->setClass($class);
                    $instruction->setParam($param);
                    $test->addInstruction($instruction);
                }
            }

            $test->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(
                'Test has been saved'
            );
            $this->_redirect('*/*/edit', ['id' => $test->getId(), 'store' => $test->getStoreId()]);
        }

    }

    public function deleteAction()
    {
        $test = Mage::getModel('magium_clairvoyant/test');
        if ($test instanceof Magium_Clairvoyant_Model_Test) {
            if ($this->getRequest()->getParam('id')) {
                $test->load($this->getRequest()->getParam('id'));
                if ($test->getId()) {
                    $test->delete();
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        'Test has been deleted'
                    );
                    $this->_redirect('*/*/index', ['store' => $test->getStoreId()]);
                    return;
                }
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            'Unable to delete the test'
        );
    }

}
