<?php

namespace Uzbek\Humo\Response\Card;

use Uzbek\Humo\Response\BaseResponse;

/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/17/2022
 * Time: 4:29 PM
 */
class Customer extends BaseResponse
{
    public array $list = [];

    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        if (! empty($attributes['result']['Customer'])) {
            foreach ($attributes['result']['Customer'] as $item) {
                $this->list[] = new CustomerItem($item);
            }
        }
    }
}
