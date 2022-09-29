<?php

namespace Uzbek\Humo\Dtos;

/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/17/2022
 * Time: 4:18 PM
 */

use Uzbek\Humo\Response\BaseResponse;

class CustomerDto extends BaseResponse
{
    public string $customerId;

    public string $bankId;

    public string $cardholderName;

    public CardDto $Card;
}
