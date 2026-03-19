<?php

namespace Domain\Catalog\Enums;

use Domain\Shared\Enums\BackedEnum;

enum SEORobot :string
{
    use BackedEnum;

    case Inactive = "Inactive";
    case Follow = "Follow";
    case NotFollow = "NotFollow";

    
}