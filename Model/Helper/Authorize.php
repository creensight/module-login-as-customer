<?php
/**
 * @copyright Copyright © 2020 CreenSight. All rights reserved.
 * @author CreenSight Development Team <magento@creensight.com>
 */

namespace CreenSight\LoginAsCustomer\Model\Helper;

use Magento\Framework\AuthorizationInterface;

/**
 * Class Authorize
 * @package CreenSight\LoginAsCustomer\Model\Helper
 */
class Authorize
{
    /**
     * @var string
     */
    const ADMIN_RESOURCE = 'CreenSight_LoginAsCustomer::allow';

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * Authorize constructor.
     *
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        AuthorizationInterface $authorization
    ) {
        $this->authorization = $authorization;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return $this->authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
