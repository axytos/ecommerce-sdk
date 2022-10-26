<?php

declare(strict_types=1);

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckResponseDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlConfirmRequestDto;
use Axytos\FinancialServices\OpenAPI\Client\Api\CheckApi;
use Axytos\FinancialServices\OpenAPI\Client\ApiException;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlConfirmRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlRequest;
use Axytos\FinancialServices\OpenAPI\Client\Model\AxytosCommonPublicAPIModelsPaymentControlPaymentControlResponse;

class PaymentControlApiAdapter implements PaymentControlApiInterface
{
    private CheckApi $checkApi;
    private DtoOpenApiModelMapper $mapper;

    public function __construct(
        CheckApi $checkApi,
        DtoOpenApiModelMapper $mapper
    ) {
        $this->checkApi = $checkApi;
        $this->mapper = $mapper;
    }

    public function paymentControlCheck(PaymentControlCheckRequestDto $requestData): PaymentControlCheckResponseDto
    {
        $paymentControlRequest = $this->mapper->toOpenApiModel($requestData, AxytosCommonPublicAPIModelsPaymentControlPaymentControlRequest::class);

        try {
            /** @var AxytosCommonPublicAPIModelsPaymentControlPaymentControlResponse */
            $response = $this->checkApi->apiV1CheckRiskPaymentcontrolCheckPost($paymentControlRequest);

            return $this->mapper->toDataTransferObject($response, PaymentControlCheckResponseDto::class);
        } catch (ApiException $e) {
            throw $e;
        }
    }

    public function paymentControlConfirm(PaymentControlConfirmRequestDto $requestData): void
    {
        $request = $this->mapper->toOpenApiModel($requestData, AxytosCommonPublicAPIModelsPaymentControlPaymentControlConfirmRequest::class);

        try {
            $this->checkApi->apiV1CheckRiskPaymentcontrolConfirmPost($request);
        } catch (ApiException $e) {
            throw $e;
        }
    }
}
