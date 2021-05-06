<?php namespace Test\Dummy\Model\Attribute\Frontend;

use Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend;
use Magento\Framework\DataObject;

class DropshipOption extends AbstractFrontend
{
    public function getValue(DataObject $object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());
        return "<b>$value</b>";
    }
}
