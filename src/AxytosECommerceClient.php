<?php

namespace Axytos\ECommerce;

use Axytos\FinancialServices\GuzzleHttp\Client;
use Axytos\FinancialServices\GuzzleHttp\ClientInterface;
use Axytos\FinancialServices\OpenAPI\Client\Api\CheckApi;
use Axytos\FinancialServices\OpenAPI\Client\Api\ErrorApi;
use Axytos\FinancialServices\OpenAPI\Client\Configuration;
use Axytos\FinancialServices\OpenAPI\Client\Api\PaymentApi;
use Axytos\FinancialServices\OpenAPI\Client\Api\PaymentsApi;
use Axytos\FinancialServices\OpenAPI\Client\Api\CredentialsApi;
use Axytos\ECommerce\Clients\ClientFacade;
use Axytos\ECommerce\DataMapping\DtoArrayMapper;
use Axytos\ECommerce\DataMapping\DtoToDtoMapper;
use Axytos\ECommerce\UserAgent\UserAgentFactory;
use Axytos\ECommerce\Clients\Invoice\InvoiceClient;
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
use Axytos\ECommerce\Abstractions\FallbackModeConfigurationInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiAdapter;
use Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface;
use Axytos\ECommerce\Clients\Checkout\StaticContentApiProxy;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClient;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiAdapter;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClientInterface;

final class AxytosECommerceClient extends ClientFacade
{
    /**
     * @param \Axytos\ECommerce\Abstractions\ApiHostProviderInterface $apiHostProvider
     * @param \Axytos\ECommerce\Abstractions\ApiKeyProviderInterface $apiKeyProvider
     * @param \Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface $paymentMethodConfiguration
     * @param \Axytos\ECommerce\Abstractions\FallbackModeConfigurationInterface $fallbackModeConfiguration
     * @param \Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface $userAgentInfoProvider
     * @param \Axytos\ECommerce\Logging\LoggerAdapterInterface $logger
     * @return \Axytos\ECommerce\DependencyInjection\Container
     */
    public static function buildContainer(
        $apiHostProvider,
        $apiKeyProvider,
        $paymentMethodConfiguration,
        $fallbackModeConfiguration,
        $userAgentInfoProvider,
        $logger
    ) {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->registerInstanceMap([
            ApiHostProviderInterface::class => $apiHostProvider,
            ApiKeyProviderInterface::class => $apiKeyProvider,
            PaymentMethodConfigurationInterface::class => $paymentMethodConfiguration,
            FallbackModeConfigurationInterface::class => $fallbackModeConfiguration,
            UserAgentInfoProviderInterface::class => $userAgentInfoProvider,
            LoggerAdapterInterface::class => $logger,
        ]);

        $containerBuilder->registerFactory(ClientInterface::class, function ($container) {
            $clientAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
            return new Client([
                'headers' => [
                    'X-ClientAddr' => $clientAddr
                ]
            ]);
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

        $containerBuilder->registerFactory(StaticContentApiProxy::class, function ($container) {
            $client = $container->get(ClientInterface::class);
            $configuration = $container->get(Configuration::class);
            return new StaticContentApiProxy($client, $configuration);
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

        $containerBuilder->registerFactory(UserAgentFactory::class, function ($container) {
            return new UserAgentFactory($container->get(UserAgentInfoProviderInterface::class));
        });

        $containerBuilder->registerFactory(CheckoutClientInterface::class, function ($container) {
            return new CheckoutClient(
                $container->get(PaymentMethodConfigurationInterface::class),
                $container->get(CheckoutApiInterface::class)
            );
        });

        $containerBuilder->registerFactory(CheckoutApiInterface::class, function ($container) {
            return new CheckoutApiAdapter(
                $container->get(ClientInterface::class),
                $container->get(StaticContentApiProxy::class)
            );
        });

        $containerBuilder->registerFactory(CredentialValidationClientInterface::class, function ($container) {
            return new CredentialValidationClient(
                $container->get(CredentialValidationApiInterface::class)
            );
        });

        $containerBuilder->registerFactory(CredentialValidationApiInterface::class, function ($container) {
            return new CredentialValidationApiAdapter(
                $container->get(CredentialsApi::class)
            );
        });

        $containerBuilder->registerFactory(ErrorReportingClientInterface::class, function ($container) {
            return new ErrorReportingClient(
                $container->get(ErrorReportingApiInterface::class),
                $container->get(LoggerAdapterInterface::class)
            );
        });

        $containerBuilder->registerFactory(ErrorReportingApiInterface::class, function ($container) {
            return new ErrorReportingApiAdapter(
                $container->get(ErrorApi::class),
                $container->get(DtoOpenApiModelMapper::class)
            );
        });

        $containerBuilder->registerFactory(InvoiceClientInterface::class, function ($container) {
            return new InvoiceClient(
                $container->get(InvoiceApiInterface::class),
                $container->get(DtoArrayMapper::class)
            );
        });

        $containerBuilder->registerFactory(InvoiceApiInterface::class, function ($container) {
            return new InvoiceApiAdapter(
                $container->get(PaymentsApi::class),
                $container->get(PaymentApi::class),
                $container->get(DtoOpenApiModelMapper::class)
            );
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
