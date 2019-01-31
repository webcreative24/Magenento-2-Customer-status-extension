<?php
/**
 * Add new attribute for customer
 *
 * Created by PhpStorm.
 *
 * @author: Sergey Yarmolich <mage.brains@gmail.com>
 */

namespace Yarmolich\CustomerStatus\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

/**
 * Class InstallData
 *
 * @package Yarmolich\CustomerStatus\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * InstallData constructor.
     *
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory  $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     *
     * @return void
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        /** @var CustomerSetup $customerSetup */
        $customerSetup
            = $this->customerSetupFactory->create(['setup' => $setup]);
        $setup->startSetup();
        $attributesInfo = [
            'customer_status' => [
                'label'        => 'Status',
                'type'         => 'text',
                'input'        => 'text',
                'position'     => 1000,
                'visible'      => true,
                'required'     => false,
                'system'       => 0,
                'user_defined' => true,
            ],
        ];
        $customerEntity = $customerSetup->getEavConfig()->getEntityType(
            'customer'
        );
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        foreach ($attributesInfo as $attributeCode => $attributeParams) {
            $customerSetup->addAttribute(
                Customer::ENTITY,
                $attributeCode,
                $attributeParams
            );
        }
        $magentoUsernameAttribute = $customerSetup->getEavConfig()
            ->getAttribute(Customer::ENTITY, 'customer_status');
        $magentoUsernameAttribute->addData(
            [
                'attribute_set_id'   => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms'      => [
                    'adminhtml_customer',
                    'customer_account_edit',
                    'customer_account_status'
                ],
            ]
        );
        $magentoUsernameAttribute->save();
        $setup->endSetup();
    }
}