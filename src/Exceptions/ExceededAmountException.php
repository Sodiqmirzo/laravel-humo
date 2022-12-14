<?php
/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 9/29/2022
 * Time: 12:28 PM
 */

namespace Uzbek\Humo\Exceptions;

class ExceededAmountException extends Exception
{
    protected $code = 20009;

    protected $message = 'Max amount for unidentified users 9.999.999';
}
