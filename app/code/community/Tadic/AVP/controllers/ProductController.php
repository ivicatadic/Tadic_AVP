<?php

class Tadic_AVP_ProductController extends Mage_Core_Controller_Front_Action
{
    protected function _requestAuthentication($msg = NULL)
    {
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Basic realm="'.$this->__('Product Preview').'"');
        if ( ! $msg) {
            $msg = $this->__('You must be authenticated to view this page.');
        }
        echo $msg;
        exit;
    }

    public function preDispatch()
    {
        parent::preDispatch();

        if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
            $this->_requestAuthentication();
        }

        try {
            $user = Mage::getModel('admin/user'); /** @var $user Mage_Admin_Model_User */
            if ( ! $user->authenticate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
                Mage::throwException($this->__('Invalid login.'));
            }
        }
        catch (Exception $e) {
            $this->_requestAuthentication($e->getMessage());
        }
    }

    /**
     * This is a straight copy/paste of
     *
     *   Mage_Catalog_ProductController::viewAction()
     *
     * Only change is that I replaced the $viewHelper so that we could
     * hook into prepareAndRender()
     */
    public function previewAction()
    {
        // Get initial data from request
        $categoryId = (int) $this->getRequest()->getParam('category', false);
        $productId  = (int) $this->getRequest()->getParam('id');
        $specifyOptions = $this->getRequest()->getParam('options');

        // Prepare helper and params
        $viewHelper = Mage::helper('tadic_avp/catalog_product_view');

        $params = new Varien_Object();
        $params->setCategoryId($categoryId);
        $params->setSpecifyOptions($specifyOptions);

        // Render page
        try {
            $viewHelper->prepareAndRender($productId, $this, $params);
        } catch (Exception $e) {
            if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
                if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
                    $this->_redirect('');
                } elseif (!$this->getResponse()->isRedirect()) {
                    $this->_forward('noRoute');
                }
            } else {
                Mage::logException($e);
                $this->_forward('noRoute');
            }
        }
    }
}
