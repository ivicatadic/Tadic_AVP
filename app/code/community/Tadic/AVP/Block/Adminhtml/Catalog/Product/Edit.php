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

        // Do not add view/preview link at the default scope
        if ( ! Mage::app()->isSingleStoreMode() && $this->getProduct()->getStore()->isAdmin()) {
            return $header;
        }

        // Add preview link only if the product is available on frontend
        if ($this->getProduct()->isAvailable()) {
            $header .= '&nbsp&nbsp<a href="'.$this->getProduct()->getUrlInStore().'" target="_blank">'.$this->__('view').'</a>';
        }
        // Preview link
        else {
            $previewUrl = $this->getUrl('tadic_avp/product/preview', array(
                'id' => $this->getProductId(),
                'store_id' => $this->getProduct()->getStoreId(),
            ));
            $header .= '&nbsp&nbsp<a href="'.$previewUrl.'" target="_blank">'.$this->__('preview').'</a>';
        }

        return $header;
    }

}
