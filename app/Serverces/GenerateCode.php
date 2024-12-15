<?php

namespace App\Serverces;

class GenerateCode
{
    public function make(int $number, string $type)
    {
        $code_outbound = $type . date('Ymd') . $number . rand(1000, 9999);

        return $code_outbound;
    }
}
