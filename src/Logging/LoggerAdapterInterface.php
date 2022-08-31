<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Logging;

interface LoggerAdapterInterface
{
    public function error(string $message): void;
    public function warning(string $message): void;
    public function info(string $message): void;
    public function debug(string $message): void;
}
