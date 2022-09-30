<?php

namespace Uzbek\Humo\Trait;

/**
 * Created by Sodikmirzo.
 * User: Sodikmirzo Sattorov ( https://github.com/Sodiqmirzo )
 * Date: 8/17/2022
 * Time: 4:10 PM
 */

use Exception;
use Ramsey\Uuid\Uuid;

trait Utils
{
    public ?string $session_id = null;

    public function getDate12(): string
    {
        return date('ymdHis');
    }

    public function generateExt(): string
    {
        try {
            $ext_id = str_replace('-', '', Uuid::uuid4()->toString());
        } catch (Exception $e) {
            $ext_id = hrtime(true);
        }

        return $ext_id;
    }

    public function getNewSessionID(): string
    {
        try {
            $this->session_id = (string) Uuid::uuid4();
        } catch (Exception $e) {
            $this->session_id = (string) time();
        }

        return $this->session_id;
    }
}
