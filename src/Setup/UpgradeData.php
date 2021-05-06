<?php namespace Test\Dummy\Setup;

/**
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author Phuong LE <sony@meincode.com> <@> - Original Author
 * @author Michael Dibbets <michael@bigbridge.nl> <@> - Modifyier
 * @copyright Copyright (c) 2019 Menincode (http://www.menincode.com)
 * @copyright Copyright (c) 2021 BigBridge (https://bigbridge.nl)
 */

use Magento\Catalog\Api\AttributeSetManagementInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeGroupRepositoryInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Api\Data\AttributeGroupInterfaceFactory;
use Magento\Eav\Api\Data\AttributeSetInterfaceFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Test\Dummy\Model\Attribute\Backend\DropshipOption;
use Test\Dummy\Model\Attribute\Frontend\DropshipOption as FrontendDropshipOption;
use Test\Dummy\Model\Attribute\Source\DropshipOptions;


/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var AttributeSetInterfaceFactory
     */
    private $attributeSetFactory;
    /**
     * @var AttributeSetManagementInterface
     */
    private $attributeSetManagement;
    /**
     * @var AttributeGroupInterfaceFactory
     */
    private $attributeGroupFactory;
    /**
     * @var AttributeGroupRepositoryInterface
     */
    private $attributeGroupRepository;

    const DROPSHIP_ATTRIBUTE = 'dropship';

    /**
     * UpgradeData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param Product $product
     * @param AttributeSetInterfaceFactory $attributeSetInterfaceFactory
     * @param AttributeSetManagementInterface $attributeSetManagement
     * @param AttributeGroupInterfaceFactory $attributeGroupFactory
     * @param AttributeGroupRepositoryInterface $attributeGroupRepository
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        Product $product,
        AttributeSetInterfaceFactory $attributeSetInterfaceFactory,
        AttributeSetManagementInterface $attributeSetManagement,
        AttributeGroupInterfaceFactory $attributeGroupFactory,
        AttributeGroupRepositoryInterface $attributeGroupRepository

    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->product = $product;
        $this->attributeSetFactory = $attributeSetInterfaceFactory;
        $this->attributeSetManagement = $attributeSetManagement;
        $this->attributeGroupFactory = $attributeGroupFactory;
        $this->attributeGroupRepository = $attributeGroupRepository;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws LocalizedException
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        if (version_compare($context->getVersion(), '0.0.1', '<')) {
            $attributes = [
                self::DROPSHIP_ATTRIBUTE => [
                    'type' => 'int',
                    'label' => 'Dropship',
                    'input' => 'select',
                    'source' => DropshipOptions::class,

                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'frontend' => FrontendDropshipOption::class,
                    'backend' => DropshipOption::class,

                    'filterable' => false,
                    'required' => true,
                    'is_used_in_grid' => false,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                    'visible' => false,
                    'is_html_allowed_on_front' => false,
                    'visible_on_front' => false
                ]
            ];
            $this->createProductAttribute($attributes);
        }

        /*
            $attributes = [
                'another_custom_attribute' => [
                    'type' => 'varchar',
                    'label' => 'Another Custom Label',
                    'input' => 'text',
                    'source' => '',
                    'filterable' => false,
                    'visible_on_front' => true,
                    'used_in_product_listing' => false,
                    'attribute_group_name' => 'My Custom Attribute Group Name',
                    'backend' => ''
                ],
                'another_custom_attribute_with_option' => [
                    'type' => 'int',
                    'label' => 'Type',
                    'input' => 'select',
                    'source' => '',
                    'filterable' => true,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'backend' => '',
                    'options' => [
                        'Option A',
                        'Option B',
                        'Option C',
                        'Option D',
                        'Option E',
                        'Option F'
                    ]
                ],
                'another_custom_attribute_boolean' =>[
                    'type' => 'int',
                    'label' => 'Shown In List',
                    'input' => 'select',
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'filterable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'attribute_group_name' => 'Another Custom Attribute Group Name',
                    'attribute_set_name' => 'my custom Attribute Set Name',
                    'backend' => ''
                ],
                'another_custom_attribute_multiselect' => [
                    'type' => 'varchar',
                    'label' => 'Collection',
                    'input' => 'multiselect',
                    'source' => '',
                    'filterable' => true,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'options' => [
                        'Collection A',
                        'Collection B',
                        'Collection C',
                        'Collection D',
                    ],
                    'attribute_group_name' => 'Another Custom Attribute Group Name',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend'
                ],
            ];

            $this->createProductAttribute($attributes);

        }*/

    }

    /**
     * Create Attribute
     * @param $attributes
     * @throws LocalizedException
     * @noinspection PhpDocMissingThrowsInspection
     */
    private function createProductAttribute($attributes)
    {
        foreach ($attributes as $attribute => $data) {

            /** @var EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create();
            $productEntity = Product::ENTITY;
            $attrSetName = null;
            $attributeGroupId = null;

            /**
             * Initialise Attribute Set Id
             */
            if (isset($data['attribute_set_name'])) {
                $attributeSetId = $eavSetup->getAttributeSetId($productEntity, $data['attribute_set_name']);

                /**
                 * If our attribute set name does not exist, we create it.
                 * By default if Magento does not find an attribute set Id, it returns the default attribute set Id
                 */
                if($attributeSetId == $eavSetup->getDefaultAttributeSetId($productEntity) && $data['attribute_set_name'] != 'Default') {
                    $attrSetName = $data['attribute_set_name'];
                    $this->createAttributeSet($attrSetName);
                    $attributeSetId = $eavSetup->getAttributeSetId($productEntity, $attrSetName);
                }
            } else {
                $attributeSetId = $this->product->getDefaultAttributeSetId();
            }

            /**
             * Initialise Attribute Group Id
             */
            if (isset($data['attribute_group_name'])) {
                $attributeGroupId = $eavSetup->getAttributeGroupId($productEntity, $attributeSetId, $data['attribute_group_name']);

                /**
                 * If our attribute group name does not exist, we create it
                 */
                if($attributeGroupId == $eavSetup->getDefaultAttributeGroupId($productEntity) && $data['attribute_group_name'] != 'General') {
                    $attributeGroupName = $data['attribute_group_name'];
                    $this->createAttributeGroup($attributeGroupName, $attrSetName);
                    $attributeGroupId = $eavSetup->getAttributeGroupId($productEntity, $attributeSetId, $attributeGroupName);
                }
            }

            /**
             * Add attributes to the eav/attribute
             */
            $eavSetup->addAttribute(
                $productEntity,
                $attribute,
                [
                    'group' => $attributeGroupId ? '' : 'General', // Let empty, if we want to set an attribute group id
                    'type' => $data['type'],
                    'backend' => $data['backend'],
                    'frontend' => '',
                    'label' => $data['label'],
                    'input' => $data['input'],
                    'class' => '',
                    'source' => $data['source'],
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => $data['filterable'],
                    'comparable' => false,
                    'visible_on_front' => $data['visible_on_front'],
                    'used_in_product_listing' => $data['used_in_product_listing'],
                    'unique' => false
                ]
            );


            /**
             * Set attribute group Id if needed
             */
            if (!is_null($attributeGroupId)) {
                /**
                 * Set the attribute in the right attribute group in the right attribute set
                 */
                $eavSetup->addAttributeToGroup($productEntity, $attributeSetId, $attributeGroupId, $attribute);
            }


            /**
             * Add options if needed
             */
            if (isset($data['options'])) {
                $options = [
                    'attribute_id' => $eavSetup->getAttributeId($productEntity, $attribute),
                    'values' => $data['options']
                ];
                $eavSetup->addAttributeOption($options);
            }
        }
    }


    /**
     * @param $attrSetName
     * @throws InputException
     * @throws NoSuchEntityException
     */
    private function createAttributeSet($attrSetName)
    {
        $defaultAttributeSetId = $this->product->getDefaultAttributeSetId();
        $attributeSet = $this->attributeSetFactory->create();
        $attributeSet->setAttributeSetName($attrSetName);
        $this->attributeSetManagement->create($attributeSet, $defaultAttributeSetId);

    }

    /**
     * @param $attributeGroupName
     * @param null $attrSetName
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function createAttributeGroup($attributeGroupName, $attrSetName = null) {

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create();
        $productEntity = Product::ENTITY;

        if ($attrSetName) {
            $this->createAttributeSet($attrSetName);
            $attributeSetId = $eavSetup->getAttributeSetId($productEntity, $attrSetName);
        } else {
            $attributeSetId = $this->product->getDefaultAttributeSetId();
        }


        $attributeGroup = $this->attributeGroupFactory->create();

        $attributeGroup->setAttributeSetId($attributeSetId);
        $attributeGroup->setAttributeGroupName($attributeGroupName);
        $this->attributeGroupRepository->save($attributeGroup);
    }
}
