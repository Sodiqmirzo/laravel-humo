<?php

namespace Uzbek\Humo\Response;

/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/17/2022
 * Time: 4:29 PM
 */
class Customer extends BaseResponse
{
    public string $customerId;

    public string $bankId;

    public string $cardholderName;

    public string $state;

    public string $language;

    public ChargeDto $Charge;

    public CardDto $Card;

    public PhoneDto $Phone;

    public EmailDto $Email;
}
