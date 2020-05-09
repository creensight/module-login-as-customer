<?php
/**
 * @copyright Copyright © 2020 CreenSight. All rights reserved.
 * @author CreenSight Development Team <magento@creensight.com>
 */

namespace CreenSight\LoginAsCustomer\Controller\Adminhtml\Login;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Url;
use CreenSight\LoginAsCustomer\Model\LogFactory;
use CreenSight\LoginAsCustomer\Model\Helper\ConfigProvider;
use CreenSight\LoginAsCustomer\Model\Helper\Authorize;
use CreenSight\LoginAsCustomer\Model\Helper\LoginToken;
use CreenSight\LoginAsCustomer\Model\Helper\GetCustomerStore;

/**
 * Class Index
 * @package CreenSight\LoginAsCustomer\Controller\Adminhtml\Login
 */
class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'CreenSight_LoginAsCustomer::allow';

    /**
     * @var CustomerFactory
     */
    private $customerFactory;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var Authorize
     */
    private $authorize;

    /**
     * @var LoginToken
     */
    private $loginToken;

    /**
     * @var GetCustomerStore
     */
    private $getCustomerStore;

    /**
     * @var LogFactory
     */
    private $logFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param CustomerFactory $customerFactory
     * @param ConfigProvider $configProvider
     * @param Authorize $authorize
     * @param LoginToken $loginToken
     * @param GetCustomerStore $getCustomerStore
     * @param LogFactory $logFactory
     */
    public function __construct(
        Context $context,
        CustomerFactory $customerFactory,
        ConfigProvider $configProvider,
        Authorize $authorize,
        LoginToken $loginToken,
        GetCustomerStore $getCustomerStore,
        LogFactory $logFactory
    ) {
        $this->customerFactory = $customerFactory;
        $this->configProvider = $configProvider;
        $this->authorize = $authorize;
        $this->loginToken = $loginToken;
        $this->getCustomerStore = $getCustomerStore;
        $this->logFactory = $logFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute()
    {
        if (!($this->configProvider->execute(ConfigProvider::XML_PATH_GENERAL_ENABLED) && $this->authorize->execute())) {
            $this->messageManager->addErrorMessage(__('Module is not enabled.'));
            return $this->_redirect('customer');
        }

        $customerId = $this->getRequest()->getParam('id');
        $customer = $this->customerFactory->create()->load($customerId);

        if (!$customer || !$customer->getId()) {
            $this->messageManager->addErrorMessage(__('Customer does not exist.'));
            return $this->_redirect('customer');
        }

        $user = $this->_auth->getUser();
        $token = $this->loginToken->execute();

        $log = $this->logFactory->create();

        $log->setData([
            'token'          => $token,
            'admin_id'       => $user->getId(),
            'admin_email'    => $user->getEmail(),
            'admin_name'     => $user->getName(),
            'customer_id'    => $customer->getId(),
            'customer_email' => $customer->getEmail(),
            'customer_name'  => $customer->getName()
        ])->save();

        $store = $this->getCustomerStore->execute($customer);

        $loginUrl = $this->_objectManager->create(Url::class)
            ->setScope($store)
            ->getUrl('loginascustomer/login/index', ['key' => $token, '_nosid' => true]);

        return $this->getResponse()->setRedirect($loginUrl);
    }
}
