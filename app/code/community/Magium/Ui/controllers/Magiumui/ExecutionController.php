<?php


class Magium_Ui_Magiumui_ManagementController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_title('System Configuration')->_title('Magium');
        $this->_setActiveMenu('system/magium');
        $this->renderLayout();
    }

}