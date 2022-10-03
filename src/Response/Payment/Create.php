<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/24/2022
 * Time: 6:06 PM
 */

namespace Uzbek\Humo\Response\Payment;

use Uzbek\Humo\Response\BaseResponse;

class Create extends BaseResponse
{
    private ?Details $_details = null;

    public function __construct(array $params)
    {
        parent::__construct($params['PaymentResponse'] ?? []);
    }

    public function getDetails(): Details
    {
        if ($this->_details === null) {
            $details = $this->getAttribute('details', []);
            $items = $details['item'] ?? [];

            $this->_details = new Details($this->getFormattedItems($items));
        }

        return $this->_details;
    }
}
