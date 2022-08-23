<?php declare(strict_types=1);

namespace Axytos\ECommerce\Logging;

interface LoggerAdapterInterface {
    function error(string $message): void;
    function warning(string $message): void;
    function info(string $message): void;
    function debug(string $message): void;
}