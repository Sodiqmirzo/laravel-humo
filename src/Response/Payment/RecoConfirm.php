<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 9/29/2022
 * Time: 12:38 PM
 */

namespace Uzbek\Humo\Response\Payment;

/**
 * Class RecoConfirm
 *
 * @property-read int|null payment_id
 * @property-read array|null details
 * @property-read int|null action
 */
class RecoConfirm extends BaseResponse
{
    public function __construct(array $params)
    {
        parent::__construct($params['PaymentResponse'] ?? []);
    }

    public function isOk(): bool
    {
        return (int) $this->action === 10;
    }
}
