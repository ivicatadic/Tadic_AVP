<?php
/*
 * @author  Ivica Tadić <ivica.tadic@ymail.com>
 */

class Tadic_AVP_Block_Adminhtml_Catalog_Product_Edit extends Mage_Adminhtml_Block_Catalog_Product_Edit
{

    public function getHeader()
    {
        $header = parent::getHeader();

        if ( ! $this->getProduct()->getId()) {
            return $header;
        }

        if ( ! Mage::app()->isSingleStoreMode()) {
            // Do not add view/preview link at the default scope
            if ($this->getProduct()->getStore()->isAdmin()) {
                return $header;
            }
            // Do not add view/preview link if the product is not assigned to the current store
            if ( ! in_array($this->getProduct()->getStoreId(), $this->getProduct()->getStoreIds())) {
                return $header;
            }
        }

        // Add preview link only if the product is available on frontend
        if ($this->getProduct()->isAvailable()) {
            $header .= '&nbsp&nbsp<a href="'.$this->getProduct()->getUrlInStore().'" target="_blank">'.$this->__('view').'</a>';
        }
        // Preview link
        else {
            $previewUrl = Mage::getModel('core/url')->setStore($this->getProduct()->getStore())->getUrl('tadic_avp/product/preview', array(
                'id' => $this->getProductId()
            ));
            $header .= '&nbsp&nbsp<a href="'.$previewUrl.'" target="_blank">'.$this->__('preview').'</a>';
        }

        return $header;
    }

}
