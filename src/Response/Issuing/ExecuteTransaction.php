<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 9/30/2022
 * Time: 6:12 PM
 */

namespace Uzbek\Humo\Response\Issuing;

use Uzbek\Humo\Response\BaseResponse;

/**
 * Class ExecuteTransaction
 *
 * @property-read string INTERNAL_NO
 */
class ExecuteTransaction extends BaseResponse
{
    public function __construct(array $attributes)
    {
        $data = ['INTERNAL_NO' => $attributes['multiRef'][1]['INTERNAL_NO'] ?? null];
        parent::__construct($data);
    }
}
