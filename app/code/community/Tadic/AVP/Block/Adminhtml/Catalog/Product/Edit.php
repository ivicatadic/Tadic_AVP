<?php
/*
 * @author  Ivica Tadić <ivica.tadic@ymail.com>
 */

class Tadic_AVP_Block_Adminhtml_Catalog_Product_Edit extends Mage_Adminhtml_Block_Catalog_Product_Edit
{

	public function getHeader()
	{
		$header = parent::getHeader();

		if ($this->getProduct()->getId()) {
			$url = $this->getProduct()->getUrlInStore();
			$header .= "&nbsp&nbsp<a href='$url' target='_blank'>view product</a>";
		}

		return $header;
	}

}