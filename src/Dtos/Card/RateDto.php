<?php

namespace Uzbek\Humo\Dtos;

/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/17/2022
 * Time: 4:18 PM
 */

use Uzbek\Humo\Response\BaseResponse;

class RateDto extends BaseResponse
{
    public string $owner;
    public string $rateSet;
    public int $directQuotation;
    public string $baseCurrency;
    public string $counterCurrency;
    public string $effectiveDate;
    public string $sellRate;
    public string $buyRate;
}
