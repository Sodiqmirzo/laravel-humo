<?php

namespace Uzbek\Humo\Dtos\Card;

/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/17/2022
 * Time: 4:18 PM
 */

use Uzbek\Humo\Response\Card\BaseResponse;

class IiacsCardDto extends BaseResponse
{
    public string $institutionId;

    public string $primaryAccountNumber;

    public string $effectiveDate;

    public string $updateDate;

    public int $prefixNumber;

    public string $expiry;

    public int $cardSequenceNumber;

    public string $cardholderId;

    public string $nameOnCard;

    public string $accountRestrictionsFlag;

    public string $commissionGroup;

    public string $cardUserId;

    public string $additionalInfo;

    public string $riskGroup;

    public string $riskGroup2;

    public string $bankC;

    public int $pinTryCount;

    public IiacsCardStatusesDto $statuses;

    public IiacsCardMbDto $mb;
}
