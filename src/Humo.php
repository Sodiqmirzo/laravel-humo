<?php

namespace Uzbek\Humo;

use Uzbek\Humo\Models\Card;

class Humo
{
    public function card(): Card
    {
        return new Card();
    }
}
