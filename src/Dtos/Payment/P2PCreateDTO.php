<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/19/2022
 * Time: 11:20 AM
 */

namespace Uzbek\Humo\Dtos\Payment;

class P2PCreateDTO
{
    public bool $isBank;

    public string $pan;

    public string $expiry;

    public string $pan2;

    public int $amount;

    public string $merchant_id;

    public string $terminal_id;

    public function getSwitchId(): int
    {
        return $this->isBank ? 48 : 50;
    }
}
