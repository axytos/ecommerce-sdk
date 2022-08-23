<?php declare(strict_types=1);

namespace Axytos\ECommerce\DependencyInjection;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct("Container does not contain ID: $id");
    }
}