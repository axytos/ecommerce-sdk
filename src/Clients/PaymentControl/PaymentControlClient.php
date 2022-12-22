<?php

namespace Axytos\ECommerce\Clients\PaymentControl;

use Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface;
use Axytos\ECommerce\DataTransferObjects\CheckDecisions;
use Axytos\ECommerce\DataTransferObjects\PaymentTypeSecurities;
use Axytos\ECommerce\DataTransferObjects\PaymentControlCheckRequestDto;
use Axytos\ECommerce\DataTransferObjects\PaymentControlConfirmRequestDto;
use DateTimeImmutable;
use Exception;

class PaymentControlClient implements PaymentControlClientInterface
{
    /**
     * @var \Axytos\ECommerce\Clients\PaymentControl\PaymentControlApiInterface
     */
    private $paymentControlApi;
    /**
     * @var \Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface
     */
    private $paymentMethodConfiguration;
    /**
     * @var \Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderDataHashCalculator
     */
    private $paymentControlOrderDataHashCalculator;

    const PROOF_OF_INTEREST = 'AAE';
    const REQUEST_MODE = 'SingleStep';

    public function __construct(
        PaymentControlApiInterface $paymentControlApi,
        PaymentMethodConfigurationInterface $paymentMethodConfiguration,
        PaymentControlOrderDataHashCalculator $paymentControlOrderDataHashCalculator
    ) {
        $this->paymentControlApi = $paymentControlApi;
        $this->paymentMethodConfiguration = $paymentMethodConfiguration;
        $this->paymentControlOrderDataHashCalculator = $paymentControlOrderDataHashCalculator;
    }

    /**
     * @param \Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderData $data
     * @param \Axytos\ECommerce\Clients\PaymentControl\PaymentControlCacheInterface $paymentControlCache
     * @return string
     */
    public function check($data, $paymentControlCache)
    {
        try {
            $paymentTypeSecurity = $this->getPaymentTypeSecurity($data->paymentMethodId);

            if ($paymentTypeSecurity === null) {
                return PaymentControlAction::COMPLETE_ORDER;
            }

            $currentHash = $this->paymentControlOrderDataHashCalculator->computeOrderDataHash($data);

            if ($paymentTypeSecurity !== PaymentTypeSecurities::UNSAFE) {
                $previousCheckResponse = $paymentControlCache->getCheckResponse();
                if ($previousCheckResponse !== null) {
                    $expirationTimestamp = $previousCheckResponse->transactionMetadata->transactionExpirationTimestamp;
                    $currentTime = new DateTimeImmutable();

                    if ($currentTime < $expirationTimestamp) {
                        $previousHash = $paymentControlCache->getCheckRequestHash();

                        if ($previousHash === $currentHash) {
                            return PaymentControlAction::COMPLETE_ORDER;
                        }
                    }
                }
            }

            $requestData = new PaymentControlCheckRequestDto();
            $requestData->requestMode = self::REQUEST_MODE;
            $requestData->proofOfInterest = self::PROOF_OF_INTEREST;
            $requestData->paymentTypeSecurity = $paymentTypeSecurity;
            $requestData->personalData = $data->personalData;
            $requestData->invoiceAddress = $data->invoiceAddress;
            $requestData->deliveryAddress = $data->deliveryAddress;
            $requestData->basket = $data->basket;

            $response = $this->paymentControlApi->paymentControlCheck($requestData);

            $paymentControlCache->setCheckResponse($response);
            $paymentControlCache->setCheckRequestHash($currentHash);

            return $this->getPaymentControlAction($paymentTypeSecurity, $response->decision);
        } catch (\Throwable $th) {
            throw new PaymentControlCheckFailedException($th);
        } catch (\Exception $th) { // @phpstan-ignore-line / php5 compatibility
            throw new PaymentControlCheckFailedException($th);
        }
    }

    /**
     * @param \Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderData $data
     * @param \Axytos\ECommerce\Clients\PaymentControl\PaymentControlCacheInterface $paymentControlCache
     * @return void
     */
    public function confirm($data, $paymentControlCache)
    {
        try {
            $paymentMethodId = $data->paymentMethodId;

            $checkResponse = $paymentControlCache->getCheckResponse();

            if ($checkResponse === null) {
                throw new Exception('$checkResponse should not be null');
            }

            if ($this->paymentMethodConfiguration->isSafe($paymentMethodId)) {
                $requestData = new PaymentControlConfirmRequestDto();
                $requestData->paymentTypeSecurity = PaymentTypeSecurities::SAFE;
                $requestData->personalData = $data->personalData;
                $requestData->invoiceAddress = $data->invoiceAddress;
                $requestData->deliveryAddress = $data->deliveryAddress;
                $requestData->basket = $data->basket;
                $requestData->paymentControlResponse = $checkResponse;

                $this->paymentControlApi->paymentControlConfirm($requestData);
            }

            if ($this->paymentMethodConfiguration->isUnsafe($paymentMethodId)) {
                $requestData = new PaymentControlConfirmRequestDto();
                $requestData->paymentTypeSecurity = PaymentTypeSecurities::UNSAFE;
                $requestData->personalData = $data->personalData;
                $requestData->invoiceAddress = $data->invoiceAddress;
                $requestData->deliveryAddress = $data->deliveryAddress;
                $requestData->basket = $data->basket;
                $requestData->paymentControlResponse = $checkResponse;

                $this->paymentControlApi->paymentControlConfirm($requestData);
            }
        } catch (\Throwable $th) {
            throw new PaymentControlConfirmFailedException($th);
        } catch (\Exception $th) { // @phpstan-ignore-line / php5 compatibility
            throw new PaymentControlConfirmFailedException($th);
        }
    }

    /**
     * @return string|null
     * @param string $paymentMethodId
     */
    private function getPaymentTypeSecurity($paymentMethodId)
    {
        $paymentMethodId = (string) $paymentMethodId;
        if ($this->paymentMethodConfiguration->isIgnored($paymentMethodId)) {
            return null;
        }

        if ($this->paymentMethodConfiguration->isSafe($paymentMethodId)) {
            return PaymentTypeSecurities::SAFE;
        }

        if ($this->paymentMethodConfiguration->isUnsafe($paymentMethodId)) {
            return PaymentTypeSecurities::UNSAFE;
        }

        return null;
    }

    /**
     * @param string|null $paymentTypeSecurity
     * @param string|null $decision
     * @return string
     */
    private function getPaymentControlAction($paymentTypeSecurity, $decision)
    {
        if ($paymentTypeSecurity === PaymentTypeSecurities::SAFE) {
            switch ($decision) {
                case CheckDecisions::REJECT:
                    return PaymentControlAction::CANCEL_ORDER;
                default:
                    return PaymentControlAction::COMPLETE_ORDER;
            }
        }

        if ($paymentTypeSecurity === PaymentTypeSecurities::UNSAFE) {
            switch ($decision) {
                case CheckDecisions::SAFE:
                    return PaymentControlAction::CHANGE_PAYMENT_METHOD;
                case CheckDecisions::UNSAFE:
                    return PaymentControlAction::COMPLETE_ORDER;
                case CheckDecisions::REJECT:
                    return PaymentControlAction::CANCEL_ORDER;
                default:
                    return PaymentControlAction::COMPLETE_ORDER;
            }
        }

        return PaymentControlAction::COMPLETE_ORDER;
    }
}
