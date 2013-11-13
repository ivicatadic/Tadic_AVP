<?php

class Tadic_AVP_Helper_Catalog_Product extends Mage_Catalog_Helper_Product
{
    /**
     * Normally this methods check to see whether the product is configured
     * to be visible on the frontend.  We're overloading that so that
     * the product will actually show up as long as the url hash key
     * is correct.
     *
     * @param int|Mage_Catalog_Model_Product $product
     * @param string $where
     * @return bool
     */
    public function canShow($product, $where = 'catalog')
    {
        if ($this->_validateUrlHash()) {
            return true;
        }

        return parent::canShow($product, $where);
    }

    protected function _validateUrlHash()
    {
        $key = Mage::app()->getRequest()->getParam('key');
        if (!$key) {
            return false;
        }

        $productId = Mage::app()->getRequest()->getParam('id');
        if ($key != $this->getHashForProduct($productId)) {
            return false;
        }

        return true;
    }

    public function getHashForProduct($productId)
    {
        $cryptKey = (string)Mage::getConfig()->getNode('global/crypt/key');
        $hash = sha1($productId . $cryptKey);

        return $hash;
    }
}