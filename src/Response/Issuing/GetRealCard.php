<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 10/6/2022
 * Time: 11:25 AM
 */

namespace Uzbek\Humo\Response\Issuing;

use Uzbek\Humo\Response\BaseResponse;

/**
 * Class GetRealCard
 *
 * @property string $card
 */
class GetRealCard extends BaseResponse
{
    public function __construct(array $attributes)
    {
        $card = $attributes['multiRef'][1]['RCARD'] ?? null;
        parent::__construct(['card' => $card]);
    }
}
