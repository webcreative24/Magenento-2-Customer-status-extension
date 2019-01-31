<?php
/**
 *
 *
 * Created by PhpStorm.
 *
 * @author: Sergey Yarmolich <mage.brains@gmail.com>
 */

namespace Yarmolich\CustomerStatus\Controller\Account;

use Magento\Framework\App\ResponseInterface;

class saveStatus extends \Magento\Framework\App\Action\Action
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
     * saveStatus constructor.
     *
     * @param \Magento\Framework\App\Action\Context             $context
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Model\Session                   $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Save Status action
     *
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $post = (array)$this->getRequest()->getPost();

        if (!empty($post)) {
            // Retrieve form data
            $customerStatus = $post['customer_status'];
            $customer = $this->customerRepository->getById(
                $this->customerSession->getCustomerId()
            );
            $customer->setCustomAttribute('customer_status', $customerStatus);
            $this->customerRepository->save($customer);
            $this->messageManager->addSuccessMessage(
                'Status Updated to "'.$customerStatus.'"'
            );
        }

        $this->_redirect('*/*/');
    }
}