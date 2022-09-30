<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/24/2022
 * Time: 6:39 PM
 */

namespace Uzbek\Humo\Response\Payment;

/**
 * Class Confirm
 *
 * @property-read string paymentID
 * @property-read ConfirmDetails details
 * @property-read string action
 */
class Confirm extends BaseResponse
{
    private ?ConfirmDetails $_details = null;

    public function __construct(array $params)
    {
        parent::__construct($params['PaymentResponse'] ?? []);
    }

    public function getDetails(): ConfirmDetails
    {
        if ($this->_details === null) {
            $details = $this->getAttribute('details', []);
            $items = $details['item'] ?? [];

            $this->_details = new ConfirmDetails($this->getFormattedItems($items));
        }

        return $this->_details;
    }
}
