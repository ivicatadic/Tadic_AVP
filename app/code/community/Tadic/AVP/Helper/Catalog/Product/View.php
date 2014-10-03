<?php

class Tadic_AVP_Helper_Catalog_Product_View extends Mage_Catalog_Helper_Product_View
{
    /**
     * This is a straight up copy/paste from
     *
     *   Mage_Catalog_Helper_Product_View::prepareAndRender()
     *
     * The only change is to replace the $productHelper so that we can hook
     * into the canShow() method.
     *
     * @param int $productId
     * @param Mage_Core_Controller_Front_Action $controller
     * @param null $params
     * @return $this|Mage_Catalog_Helper_Product_View
     * @throws Mage_Core_Exception
     */
    public function prepareAndRender($productId, $controller, $params = null)
    {
        $productHelper = Mage::helper('tadic_avp/catalog_product');
        if (!$params) {
            $params = new Varien_Object();
        }

        // Standard algorithm to prepare and rendern product view page
        $product = $productHelper->initProduct($productId, $controller, $params);
        if (!$product) {
            throw new Mage_Core_Exception($this->__('Product is not loaded'), $this->ERR_NO_PRODUCT_LOADED);
        }

        $buyRequest = $params->getBuyRequest();
        if ($buyRequest) {
            $productHelper->prepareProductOptions($product, $buyRequest);
        }

        if ($params->hasConfigureMode()) {
            $product->setConfigureMode($params->getConfigureMode());
        }

        Mage::dispatchEvent('catalog_controller_product_view', array('product' => $product));

        if ($params->getSpecifyOptions()) {
            $notice = $product->getTypeInstance(true)->getSpecifyOptionMessage();
            Mage::getSingleton('catalog/session')->addNotice($notice);
        }

        Mage::getSingleton('catalog/session')->setLastViewedProductId($product->getId());

        $this->initProductLayout($product, $controller);

        $controller->initLayoutMessages(array('catalog/session', 'tag/session', 'checkout/session'))
            ->renderLayout();

        return $this;
    }
}
