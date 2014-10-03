<?php

class Tadic_AVP_Helper_Catalog_Product extends Mage_Catalog_Helper_Product
{
    /**
     * Check if a product can be shown
     *
     * @param int|Mage_Catalog_Model_Product $product
     * @param string $where
     * @return bool
     */
    public function canShow($product, $where = 'catalog')
    {
        if (is_int($product)) {
            $product = Mage::getModel('catalog/product')->load($product); /** @var $product Mage_Catalog_Model_Product */
        }
        if ( ! $product->getId()) {
            return FALSE;
        }
        return TRUE;
    }
}
