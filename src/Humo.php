<?php

namespace Uzbek\Humo;

use Uzbek\Humo\Models\AccessGateway;
use Uzbek\Humo\Models\Card;
use Uzbek\Humo\Models\Issuing;
use Uzbek\Humo\Models\Payment;

class Humo
{
    public function card(): Card
    {
        return new Card();
    }

    public function accessGateway(): AccessGateway
    {
        return new AccessGateway();
    }

    public function issuing(): Issuing
    {
        return new Issuing();
    }

    public function payment(): Payment
    {
        return new Payment();
    }
}
