<?php

namespace Axytos\ECommerce\Logging;

interface LoggerAdapterInterface
{
    /**
     * @param string $message
     *
     * @return void
     */
    public function error($message);

    /**
     * @param string $message
     *
     * @return void
     */
    public function warning($message);

    /**
     * @param string $message
     *
     * @return void
     */
    public function info($message);

    /**
     * @param string $message
     *
     * @return void
     */
    public function debug($message);
}
