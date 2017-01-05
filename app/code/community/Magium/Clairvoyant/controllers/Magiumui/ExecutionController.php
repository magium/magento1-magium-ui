<?php


class Magium_Clairvoyant_Magiumui_ExecutionController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        if (!Mage::app()->getRequest()->getParam('event')) {
            $integrations = Mage::getConfig()->getNode('magium/execution')->asArray();
            $event = array_shift(array_keys($integrations));
            Mage::app()->getRequest()->setParam('event', $event);
        }
        $this->loadLayout();
        $this->_title('System Configuration')->_title('Magium');
        $this->_setActiveMenu('system/magium');
        $this->renderLayout();
    }

    public function assignAction()
    {
        $model = Mage::getModel('magium_clairvoyant/event');
        if ($model instanceof Magium_Clairvoyant_Model_Event) {
            $model->setStoreId($this->getRequest()->getParam('store', 0));
            $model->setEvent($this->getRequest()->getParam('event'));
            $model->setTestId($this->getRequest()->getParam('id'));
            if (!$model->validate()) {
                $errors = $model->getErrors();
                foreach ($errors as $error) {
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('magium_clairvoyant')->__($error)
                    );
                }
            } else {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('magium_clairvoyant')->__(
                        'Test has been attached to event: %s',
                        $this->getRequest()->getParam('event')
                    )
                );
            }
        }
        $this->_goHome();
    }

    public function unassignAction()
    {
        $event = Mage::getModel('magium_clairvoyant/event');
        $event->load($this->getRequest()->getParam('item'));
        if ($event->getId()) {
            $event->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('magium_clairvoyant')->__(
                    'Test has been detached from event'
                )
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('magium_clairvoyant')->__(
                    'Invalid test association'
                )
            );
        }
        $this->_goHome();
    }

    protected function _goHome()
    {
        $this->_redirect(
            '*/*/index',
            [
                'store' => $this->getRequest()->getParam('store'),
                'event' => $this->getRequest()->getParam('event')
            ]
        );
    }

}
