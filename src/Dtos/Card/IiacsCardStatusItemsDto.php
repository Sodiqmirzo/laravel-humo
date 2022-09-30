<?php

namespace Uzbek\Humo\Dtos\Card;

/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/17/2022
 * Time: 4:18 PM
 */

use Uzbek\Humo\Response\Card\BaseResponse;

class IiacsCardStatusItemsDto extends BaseResponse
{
    public string $type;

    public string $actionCode;

    public string $actionDescription;

    public ?string $effectiveDate;
}
