<?php
/**
 *
 */

namespace Yarmolich\CustomerStatus\Block\Form;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\AccountManagement;

class Status extends \Magento\Customer\Block\Account\Dashboard
{

    /**
     * @param String $code
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAttribute($code)
    {
        if ($this->getCustomer()->getCustomAttribute($code) === null) {
            throw \Magento\Framework\Exception\NoSuchEntityException::singleField(
                'code',
                $code
            );
        }

        return $this->getCustomer()->getCustomAttribute($code)->getValue();
    }

}
