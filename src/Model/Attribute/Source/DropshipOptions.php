<?php namespace Test\Dummy\Model\Attribute\Source;
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class DropshipOptions extends AbstractSource
{
    const ALWAYS = 'always';
    const YES = 'yes';
    const NO = 'no';
    /**
     * Get all options
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('Always'), 'value' => self::ALWAYS],
                ['label' => __('Yes'), 'value' => self::YES],
                ['label' => __('No'), 'value' => self::NO],
            ];
        }
        return $this->_options;
    }
}
