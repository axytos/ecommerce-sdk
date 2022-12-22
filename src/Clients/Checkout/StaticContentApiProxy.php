<?php

namespace Axytos\ECommerce\Clients\Checkout;

use Axytos\FinancialServices\OpenAPI\Client\Api\StaticContentApi;

class StaticContentApiProxy extends StaticContentApi
{
    public function apiV1StaticContentCreditcheckagreementGetRequest()
    {
        return parent::apiV1StaticContentCreditcheckagreementGetRequest();
    }
}
