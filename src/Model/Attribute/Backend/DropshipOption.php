<?php /** @noinspection ALL */

namespace Test\Dummy\Model\Attribute\Backend;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\Exception\LocalizedException;

/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

class DropshipOption extends AbstractBackend
{
    public function validate($object)
    {
        return parent::validate($object);
        return true;
    }
}
