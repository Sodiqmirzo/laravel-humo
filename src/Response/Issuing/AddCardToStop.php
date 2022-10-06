<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 10/6/2022
 * Time: 11:20 AM
 */

namespace Uzbek\Humo\Response\Issuing;

use Uzbek\Humo\Response\BaseResponse;

/**
 * Class AddCardToStop
 *
 * @property-read integer response_code
 * @property-read string error_description
 * @property-read string error_action
 * @property-read string EXTERNAL_SESSION_ID
 * @property-read bool isOk
 */
class AddCardToStop extends BaseResponse
{
    public function __construct(array $params)
    {
        $params = $params['multiRef'] ?? [];
        parent::__construct($params);
    }

    /**
     * @return bool
     */
    public function getIsOk(): bool
    {
        return $this->response_code[0] === '0';
    }
}
