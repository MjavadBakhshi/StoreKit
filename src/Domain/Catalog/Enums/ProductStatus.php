<?php

namespace Domain\Catalog\Enums;

use Domain\Shared\Enums\BackedEnum;

enum ProductStatus :string
{
    use BackedEnum;

    case Active = "Active";
    case Draft = "Draft";
    case Inactive = "Inactive";
    case Archived = "Archived";

}