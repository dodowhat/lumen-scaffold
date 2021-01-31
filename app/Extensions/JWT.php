<?php

namespace App\Extensions;

use Lindelius\JWT\JWT as LindeliusJWT;
use Lindelius\JWT\Algorithm\HMAC\HS256;

class JWT extends LindeliusJWT
{
    use HS256;
}