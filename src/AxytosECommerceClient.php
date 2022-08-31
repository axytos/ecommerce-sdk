<?php

declare(strict_types=1);

namespace Axytos\ECommerce;

use Error;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Axytos\FinancialServicesAPI\Client\Api\CheckApi;
use Axytos\FinancialServicesAPI\Client\Api\ErrorApi;
use Axytos\FinancialServicesAPI\Client\Configuration;
use Axytos\FinancialServicesAPI\Client\Api\PaymentApi;
use Axytos\ECommerce\Clients\ClientFacade;
use Axytos\FinancialServicesAPI\Client\Api\PaymentsApi;
use Axytos\FinancialServicesAPI\Client\Api\CredentialsApi;
use Axytos\FinancialServicesAPI\Client\Api\StaticContentApi;
use Axytos\ECommerce\DataMapping\DtoArrayMapper;
use Axytos\ECommerce\DataMapping\DtoToDtoMapper;
use Axytos\ECommerce\UserAgent\UserAgentFactory;
use Axytos\ECommerce\Clients\Invoice\InvoiceClient;
use Axytos\ECommerce\DependencyInjection\Container;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\ECommerce\Clients\Checkout\CheckoutClient;
use Axytos\ECommerce\Clients\Invoice\InvoiceApiAdapter;
use Axytos\ECommerce\DataMapping\DtoOpenApiModelMapper;
use Axytos\ECommerce\Clients\Checkout\CheckoutApiAdapter;
use Axytos\ECommerce\Clients\Invoice\InvoiceApiInterface;
use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;
use Axytos\ECommerce\DependencyInjection\ContainerBuilder;
use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;
use Axytos\ECommerce\Clients\Checkout\CheckoutApiInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface;
use Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClient;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlClient;
use Axytos\ECommerce\Abstractions\FallbackModeConfigurationInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiAdapter;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlApiAdapter;
use Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiInterface;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlApiInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlClientInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClient;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiAdapter;
use Axytos\ECommerce\Clients\PaymentControl\PaymentControlOrderDataHashCalculator;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClientInterface;

final class AxytosECommerceClient extends ClientFacade
{
    public const CLASS_MAP = [

        UserAgentFactory::class => [
            UserAgentFactory::class
        ],

        CheckoutClient::class => [
            CheckoutClientInterface::class
        ],

        CheckoutApiAdapter::class => [
            CheckoutApiInterface::class
        ],

        PaymentControlClient::class => [
            PaymentControlClientInterface::class
        ],

        PaymentControlApiAdapter::class => [
            PaymentControlApiInterface::class
        ],

        CredentialValidationClient::class => [
            CredentialValidationClientInterface::class
        ],

        CredentialValidationApiAdapter::class => [
            CredentialValidationApiInterface::class
        ],

        ErrorReportingClient::class => [
            ErrorReportingClientInterface::class
        ],

        ErrorReportingApiAdapter::class => [
            ErrorReportingApiInterface::class
        ],

        InvoiceClient::class => [
            InvoiceClientInterface::class
        ],

        InvoiceApiAdapter::class => [
            InvoiceApiInterface::class
        ],

        PaymentControlOrderDataHashCalculator::class => [
            PaymentControlOrderDataHashCalculator::class
        ],
    ];

    public static function buildContainer(
        ApiHostProviderInterface $apiHostProvider,
        ApiKeyProviderInterface $apiKeyProvider,
        PaymentMethodConfigurationInterface $paymentMethodConfiguration,
        FallbackModeConfigurationInterface $fallbackModeConfiguration,
        UserAgentInfoProviderInterface $userAgentInfoProvider,
        LoggerAdapterInterface $logger
    ): Container {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->registerClassMap(self::CLASS_MAP);
        $containerBuilder->registerInstanceMap([
            ApiHostProviderInterface::class => $apiHostProvider,
            ApiKeyProviderInterface::class => $apiKeyProvider,
            PaymentMethodConfigurationInterface::class => $paymentMethodConfiguration,
            FallbackModeConfigurationInterface::class => $fallbackModeConfiguration,
            UserAgentInfoProviderInterface::class => $userAgentInfoProvider,
            LoggerAdapterInterface::class => $logger,
        ]);

        $containerBuilder->registerFactory(ClientInterface::class, function ($container) {
            return new Client();
        });

        $containerBuilder->registerFactory(Configuration::class, function ($container) {
            $apiHostProvider = $container->get(ApiHostProviderInterface::class);
            $apiKeyProvider = $container->get(ApiKeyProviderInterface::class);
            $userAgentFactory = $container->get(UserAgentFactory::class);
            $configuration = new Configuration();
            $configuration->setHost($apiHostProvider->getApiHost());
            $configuration->setUserAgent($userAgentFactory->getUserAgent());
            $configuration->setApiKey('X-API-KEY', $apiKeyProvider->getApiKey());
            return $configuration;
        });

        $containerBuilder->registerFactory(StaticContentApi::class, function ($container) {
            $client = $container->get(ClientInterface::class);
            $configuration = $container->get(Configuration::class);
            return new StaticContentApi($client, $configuration);
        });

        $containerBuilder->registerFactory(CredentialsApi::class, function ($container) {
            $client = $container->get(ClientInterface::class);
            $configuration = $container->get(Configuration::class);
            return new CredentialsApi($client, $configuration);
        });

        $containerBuilder->registerFactory(CheckApi::class, function ($container) {
            $client = $container->get(ClientInterface::class);
            $configuration = $container->get(Configuration::class);
            return new CheckApi($client, $configuration);
        });

        $containerBuilder->registerFactory(ErrorApi::class, function ($container) {
            $client = $container->get(ClientInterface::class);
            $configuration = $container->get(Configuration::class);
            return new ErrorApi($client, $configuration);
        });

        $containerBuilder->registerFactory(PaymentsApi::class, function ($container) {
            $client = $container->get(ClientInterface::class);
            $configuration = $container->get(Configuration::class);
            return new PaymentsApi($client, $configuration);
        });

        $containerBuilder->registerFactory(PaymentApi::class, function ($container) {
            $client = $container->get(ClientInterface::class);
            $configuration = $container->get(Configuration::class);
            return new PaymentApi($client, $configuration);
        });

        $containerBuilder->registerFactory(DtoOpenApiModelMapper::class, function () {
            return new DtoOpenApiModelMapper();
        });

        $containerBuilder->registerFactory(DtoArrayMapper::class, function () {
            return new DtoArrayMapper();
        });

        $containerBuilder->registerFactory(DtoToDtoMapper::class, function () {
            return new DtoToDtoMapper();
        });

        return $containerBuilder->build();
    }

    public function __construct(
        ApiHostProviderInterface $apiHostProvider,
        ApiKeyProviderInterface $apiKeyProvider,
        PaymentMethodConfigurationInterface $paymentMethodConfiguration,
        FallbackModeConfigurationInterface $fallbackModeConfiguration,
        UserAgentInfoProviderInterface $userAgentInfoProvider,
        LoggerAdapterInterface $logger
    ) {
        parent::__construct(self::buildContainer(
            $apiHostProvider,
            $apiKeyProvider,
            $paymentMethodConfiguration,
            $fallbackModeConfiguration,
            $userAgentInfoProvider,
            $logger
        ));
    }
}
