<?php

namespace Uzbek\Humo\Dtos\Card;

/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/17/2022
 * Time: 4:18 PM
 */

use Uzbek\Humo\Response\Card\BaseResponse;

class CardDto extends BaseResponse
{
    public string $pan;

    public string $label;

    public ?string $state;

    public ?string $expiry;

    public ServiceDto $Service;

    public ChargeDto $Charge;
}
