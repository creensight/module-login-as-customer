<?php
/**
 * @copyright Copyright © 2020 CreenSight. All rights reserved.
 * @author CreenSight Development Team <magento@creensight.com>
 */

namespace CreenSight\LoginAsCustomer\Controller\Login;

use Exception;
use Magento\Checkout\Model\Cart;
use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use CreenSight\LoginAsCustomer\Model\LogFactory;
use CreenSight\LoginAsCustomer\Model\Helper\ConfigProvider;
use CreenSight\LoginAsCustomer\Model\Helper\Authorize;

/**
 * Class Index
 * @package CreenSight\LoginAsCustomer\Controller\Login
 */
class Index extends Action
{
    /**
     * @var SessionFactory
     */
    private $customerSession;

    /**
     * @var AccountRedirect
     */
    private $accountRedirect;

    /**
     * @var Cart
     */
    private $checkoutCart;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var Authorize
     */
    private $authorize;

    /**
     * @var LogFactory
     */
    private $logFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param SessionFactory $customerSession
     * @param AccountRedirect $accountRedirect
     * @param Cart $checkoutCart
     * @param ConfigProvider $configProvider
     * @param Authorize $authorize
     * @param LogFactory $logFactory
     */
    public function __construct(
        Context $context,
        SessionFactory $customerSession,
        AccountRedirect $accountRedirect,
        Cart $checkoutCart,
        ConfigProvider $configProvider,
        Authorize $authorize,
        LogFactory $logFactory
    ) {
        $this->customerSession = $customerSession;
        $this->accountRedirect = $accountRedirect;
        $this->checkoutCart = $checkoutCart;
        $this->configProvider = $configProvider;
        $this->authorize = $authorize;
        $this->logFactory = $logFactory;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Forward|Redirect|ResultInterface
     */
    public function execute()
    {
        $token = $this->getRequest()->getParam('key');
        $session = $this->customerSession->create();

        $log = $this->logFactory->create()->load($token, 'token');

        if (!$log || !$log->getId() || $log->getIsLoggedIn() || !$this->configProvider->execute(ConfigProvider::XML_PATH_GENERAL_ENABLED)) {
            return $this->_redirect('noRoute');
        }

        try {
            if ($session->isLoggedIn()) {
                $session->logout();
            } else {
                $this->checkoutCart->truncate()->save();
            }
        } catch (Exception $e) {
            $this->messageManager->addNoticeMessage(__('Cannot truncate cart items.'));
        }

        try {
            $session->loginById($log->getCustomerId());
            $session->regenerateId();

            $log->setIsLoggedIn(true)
                ->save();

            $redirectUrl = $this->accountRedirect->getRedirectCookie();
            if (!$this->configProvider->execute(ConfigProvider::XML_PATH_CUSTOMER_STARTUP_REDIRECT_DASHBOARD) && $redirectUrl) {
                $this->accountRedirect->clearRedirectCookie();
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setUrl($this->_redirect->success($redirectUrl));

                return $resultRedirect;
            }
        } catch (Exception $e) {
            $this->messageManager->addError(
                __('An unspecified error occurred. Please contact us for assistance.')
            );
        }

        return $this->accountRedirect->getRedirect();
    }
}
