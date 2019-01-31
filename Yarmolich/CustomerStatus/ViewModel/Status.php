<?php
/**
 * Status View Model
 *
 * Created by PhpStorm.
 *
 * @author: Sergey Yarmolich <mage.brains@gmail.com>
 */

namespace Yarmolich\CustomerStatus\ViewModel;


class Status implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * Status constructor.
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Model\Session                   $customerSession
     */
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
    }

    /**
     * @return mixed|string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStatusHtml()
    {
        $status = '';
        if (null !== $this->customerSession->getCustomerId()) {
            $customer = $this->customerRepository->getById(
                $this->customerSession->getCustomerId()
            );

            if ($customer->getCustomAttribute('customer_status') !== null
                && $customer->getCustomAttribute('customer_status') != ''
            ) {
                return $customer->getCustomAttribute('customer_status')
                    ->getValue();
            }
        }

        return $status;
    }

}