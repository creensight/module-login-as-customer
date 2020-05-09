<?php
/**
 * @copyright Copyright © 2020 CreenSight. All rights reserved.
 * @author CreenSight Development Team <magento@creensight.com>
 */

namespace CreenSight\LoginAsCustomer\Model\Helper;

use Magento\Framework\Math\Random;

/**
 * Class LoginToken
 * @package CreenSight\LoginAsCustomer\Model\Helper
 */
class LoginToken
{
    /**
     * @var Random
     */
    private $mathRandom;

    /**
     * LoginToken constructor.
     *
     * @param Random $mathRandom
     */
    public function __construct(
        Random $mathRandom
    ) {
        $this->mathRandom = $mathRandom;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return $this->mathRandom->getUniqueHash();
    }
}
