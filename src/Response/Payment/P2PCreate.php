<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/23/2022
 * Time: 1:15 PM
 */

namespace Uzbek\Humo\Response\Payment;

use Uzbek\Humo\Response\BaseResponse;

class P2PCreate extends BaseResponse
{
    public function __construct(array $params)
    {
        parent::__construct($params['RequestResponse'] ?? []);
    }
}
