<?php

namespace Axytos\ECommerce\Clients\ErrorReporting;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiInterface;
use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto;
use Axytos\FinancialServices\OpenAPI\Client\Api\ErrorApi;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosApiModelsErrorRequestModel;

class ErrorReportingApiAdapter implements ErrorReportingApiInterface
{
    /**
     * @var \Axytos\FinancialServices\OpenAPI\Client\Api\ErrorApi
     */
    private $errorApi;
    /**
     * @var \Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper
     */
    private $mapper;

    public function __construct(
        ErrorApi $errorApi,
        DtoOpenApiModelMapper $mapper
    ) {
        $this->errorApi = $errorApi;
        $this->mapper = $mapper;
    }

    /**
     * @param \Axytos\ECommerce\DataTransferObjects\ErrorRequestModelDto $errorRequestModelDto
     * @return void
     */
    public function reportError($errorRequestModelDto)
    {
        try {
            $errorRequest = $this->mapper->toOpenApiModel($errorRequestModelDto, AxytosApiModelsErrorRequestModel::class);

            $this->errorApi->apiV1ErrorReportPost($errorRequest);
        } catch (\Throwable $th) {
            // fire and forget
        } catch (\Exception $th) { // @phpstan-ignore-line / php5 compatibility
            // fire and forget
        }
    }
}
