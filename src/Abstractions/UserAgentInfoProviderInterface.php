<?php

namespace Axytos\ECommerce\Abstractions;

interface UserAgentInfoProviderInterface
{
    /**
     * @return string
     */
    public function getPluginName();

    /**
     * @return string
     */
    public function getPluginVersion();

    /**
     * @return string
     */
    public function getShopSystemName();

    /**
     * @return string
     */
    public function getShopSystemVersion();
}
