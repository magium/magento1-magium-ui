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

    public function executeAction()
    {
        $helper = Mage::helper('magium_clairvoyant');
        if ($helper instanceof Magium_Clairvoyant_Helper_Data) {
            $test = $helper->getInstructionTestCase();
            $test->setName('testExecute');

            foreach ($this->getRequest()->getPost('injections', []) as $injection) {
                $model = $this->getRequest()->getPost('model-' . $injection);
                $pk = $this->getRequest()->getPost('primary-key-' . $injection);
                $instance = Mage::getModel($model)->load($pk);
                $test->getDi()->instanceManager()->addSharedInstance($instance, 'product');
                $test->getDi()->instanceManager()->addAlias('product', get_class($instance));
            }
            $interpolator = $test->getDi()->get(\Magium\TestCase\Configurable\Interpolator::class);
            if ($interpolator instanceof \Magium\TestCase\Configurable\Interpolator) {
                $url = $this->getRequest()->getPost('command_open');
                $url = $interpolator->interpolate($url);
                $test->setBaseUrl($url);

                $preConditions = $this->getRequest()->getPost('pre_conditions');
                $preConditions = explode("\n", $preConditions);
                $injectConditions = [];
                foreach ($preConditions as $condition) {
                    $condition = $interpolator->interpolate(trim($condition));
                    $injectConditions[] = $condition;
                }
                $test->setPreconditions($injectConditions);

                $instructions = $test->getDi()->get(\Magium\TestCase\Configurable\InstructionsCollection::class);
                $test->setInstructions($instructions);
                if ($instructions instanceof \Magium\TestCase\Configurable\InstructionsCollection) {
                    foreach ($this->getRequest()->getPost('instructions', []) as $instruction) {
                        $class = $this->getRequest()->getPost($instruction . '_class');
                        $introspection = Mage::getModel('magium_clairvoyant/introspected');
                        $introspection->load($class, 'class');
                        if ($introspection->getId()) {
                            $param = $this->getRequest()->getPost($instruction . '_param');
                            if ($param) {
                                $param = $interpolator->interpolate($param);
                                $param = [$param];
                            }
                            $type = $introspection->getFunctionalType();
                            $reflection = new ReflectionClass($type);
                            $methods = $reflection->getMethods();
                            if ($methods) {
                                $method = $methods[0];
                                $genericInstruction = new \Magium\TestCase\Configurable\GenericInstruction(
                                    $class,
                                    $method->getName(),
                                    $param
                                );
                                $instructions->addInstruction($genericInstruction);
                            }
                        }
                    }
                }
            }
            $logger = $test->getLogger();
            $writer = Mage::getModel('magium_clairvoyant/logger_events');
            if ($writer instanceof Magium_Clairvoyant_Model_Logger_Events) {
                $logger->addWriter($writer);
                $error = null;
                try {
                    $test->run();
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
                $events = $writer->getEvents();

                $result = [
                    'passed'    => $error === null,
                    'message'   => ($error === null)?'Test passed':$error,
                    'events'    => $events
                ];

            } else {
                $result = [
                    'passed'    => false,
                    'message'   => 'Invalid logger'
                ];
            }
        } else {
            $result = [
                'passed'    => false,
                'message'   => 'Invalid helper'
            ];
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($result));

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
