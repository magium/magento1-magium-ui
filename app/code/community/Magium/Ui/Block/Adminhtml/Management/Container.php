<?php

class Magium_Ui_Block_Adminhtml_Management_TestContainer
    extends Mage_Adminhtml_Block_Widget_Form_Container
{

    protected function _construct()
    {
        parent::_construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'magium_ui';
        $this->_controller = 'adminhtml_management';
        $this->_mode = 'edit';
    }

    protected function  _prepareLayout()
    {
        $this->_addButton('execute', [
                'label' => $this->__('Execute Test')
            ]
        );
        $this->_addButton('delete', [
                'label' => $this->__('Delete Test'),
                'class' => 'delete'
            ], PHP_INT_MAX
        );
        $this->_updateButton('save', 'label', $this->__('Save Test'));

        $this->_addButton('save_and_continue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";

        return parent::_prepareLayout();
    }

    /**
     * Return the title string to show above the form
     *
     * @return string
     */
    public function getHeaderText()
    {
        $model = Mage::registry('current_distributor');
        if ($model && $model->getId()) {
            return $this->__('Edit Test');
        } else {
            return $this->__('New Test');
        }
    }
}