<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\ErrorReporting;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiInterface;
use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\FinancialServices\OpenAPI\Client\Api\ErrorApi;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsErrorRequestModel;

class ErrorReportingApiAdapter implements ErrorReportingApiInterface
{
    private ErrorApi $errorApi;
    private DtoOpenApiModelMapper $mapper;

    public function __construct(
        ErrorApi $errorApi,
        DtoOpenApiModelMapper $mapper
    ) {
        $this->errorApi = $errorApi;
        $this->mapper = $mapper;
    }

    public function reportError(ErrorRequestModelDto $errorRequestModelDto): void
    {
        try {
            $errorRequest = $this->mapper->toOpenApiModel($errorRequestModelDto, AxytosApiModelsErrorRequestModel::class);

            $this->errorApi->apiV1ErrorReportPost($errorRequest);
        } catch (\Throwable $th) {
            // fire and forget
        }
    }
}
