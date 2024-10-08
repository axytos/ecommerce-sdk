<?php

namespace Axytos\ECommerce\DependencyInjection;

class NotFoundException extends \Exception
{
    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $id = (string) $id;
        parent::__construct("Container does not contain ID: {$id}");
    }
}
