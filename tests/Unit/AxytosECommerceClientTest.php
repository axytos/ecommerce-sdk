<?php

namespace Axytos\ECommerce\Tests;

use Axytos\ECommerce\Abstractions\ApiHostProviderInterface;
use Axytos\ECommerce\Abstractions\ApiKeyProviderInterface;
use Axytos\ECommerce\Abstractions\FallbackModeConfigurationInterface;
use Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface;
use Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface;
use Axytos\ECommerce\UserAgent\UserAgentFactory;
use Axytos\ECommerce\Clients\Checkout\CheckoutClient;
use Axytos\ECommerce\Clients\Checkout\CheckoutApiAdapter;
use Axytos\ECommerce\Clients\Checkout\CheckoutApiInterface;
use Axytos\ECommerce\Clients\Checkout\CheckoutClientInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClient;
use Axytos\ECommerce\Clients\Invoice\InvoiceApiAdapter;
use Axytos\ECommerce\Clients\Invoice\InvoiceApiInterface;
use Axytos\ECommerce\Clients\Invoice\InvoiceClientInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClient;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiAdapter;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingApiInterface;
use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClient;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiAdapter;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationApiInterface;
use Axytos\ECommerce\Clients\CredentialValidation\CredentialValidationClientInterface;
use Axytos\ECommerce\AxytosECommerceClient;
use Axytos\ECommerce\Logging\LoggerAdapterInterface;
use Axytos\FinancialServices\GuzzleHttp\Client;
use Axytos\FinancialServices\GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;

class AxytosECommerceClientTest extends TestCase
{
    /**
     * @var \Axytos\ECommerce\Abstractions\ApiHostProviderInterface
     */
    private $apiHostProvider;
    /**
     * @var \Axytos\ECommerce\Abstractions\ApiKeyProviderInterface
     */
    private $apiKeyProvider;
    /**
     * @var \Axytos\ECommerce\Abstractions\PaymentMethodConfigurationInterface
     */
    private $paymentMethodConfiguration;
    /**
     * @var \Axytos\ECommerce\Abstractions\FallbackModeConfigurationInterface
     */
    private $fallbackModeConfiguration;
    /**
     * @var \Axytos\ECommerce\Abstractions\UserAgentInfoProviderInterface
     */
    private $userAgentInfoProvider;
    /**
     * @var \Axytos\ECommerce\Logging\LoggerAdapterInterface
     */
    private $logger;

    /**
     * @return void
     * @before
     */
    public function beforeEach()
    {
        $this->apiHostProvider = $this->createMock(ApiHostProviderInterface::class);
        $this->apiKeyProvider = $this->createMock(ApiKeyProviderInterface::class);
        $this->paymentMethodConfiguration = $this->createMock(PaymentMethodConfigurationInterface::class);
        $this->fallbackModeConfiguration = $this->createMock(FallbackModeConfigurationInterface::class);
        $this->userAgentInfoProvider = $this->createMock(UserAgentInfoProviderInterface::class);
        $this->logger = $this->createMock(LoggerAdapterInterface::class);
    }

    /**
     * @return \Axytos\ECommerce\DependencyInjection\Container
     */
    private function buildContainer()
    {
        return AxytosECommerceClient::buildContainer(
            $this->apiHostProvider,
            $this->apiKeyProvider,
            $this->paymentMethodConfiguration,
            $this->fallbackModeConfiguration,
            $this->userAgentInfoProvider,
            $this->logger
        );
    }

    /**
     * @return array<string>
     */
    private function getExpectedContainderIds()
    {
        $result = [];

        foreach (self::CLASS_MAP as $className => $aliasNames) {
            $result = array_merge($result, $aliasNames);
        }

        return $result;
    }

    /**
     * @return void
     */
    public function test_AxytosECommerceClient_can_be_constructed()
    {
        $client = new AxytosECommerceClient($this->apiHostProvider, $this->apiKeyProvider, $this->paymentMethodConfiguration, $this->fallbackModeConfiguration, $this->userAgentInfoProvider, $this->logger);
        $this->assertNotNull($client);
    }

    /**
     * @return void
     */
    public function test_buildContainer_all_classes_registered()
    {
        $container = $this->buildContainer();

        $ids = $this->getExpectedContainderIds();

        foreach ($ids as $id) {
            $this->assertTrue($container->has($id), "Missng $id");
        }
    }

    /**
     * @return void
     */
    public function test_buildContainer_all_registered_classes_can_be_resolved()
    {
        $container = $this->buildContainer();

        /**
         * @var string[]
         * @phpstan-var class-string[]
         */
        $keys = $container->keys();

        foreach ($keys as $key) {
            $classOrInterfaceExists = class_exists($key, true) || interface_exists($key, true);
            $this->assertTrue($classOrInterfaceExists, "Class of Interface $key does not exist!");

            $instance = $container->get($key);
            $this->assertInstanceOf($key, $instance);
        }
    }

    /**
     * @return void
     */
    public function test_buildContainer_ApiHostProviderInterface_can_be_resolved()
    {
        $container = $this->buildContainer();

        $instance = $container->get(ApiHostProviderInterface::class);

        $this->assertInstanceOf(ApiHostProviderInterface::class, $instance);
        $this->assertSame($this->apiHostProvider, $instance);
    }

    /**
     * @return void
     */
    public function test_ApiKeyProviderInterface_can_be_resolved()
    {
        $container = $this->buildContainer();

        $instance = $container->get(ApiKeyProviderInterface::class);

        $this->assertInstanceOf(ApiKeyProviderInterface::class, $instance);
        $this->assertSame($this->apiKeyProvider, $instance);
    }

    /**
     * @return void
     */
    public function test_PaymentMethodConfigurationInterface_can_be_resolved()
    {
        $container = $this->buildContainer();

        $instance = $container->get(PaymentMethodConfigurationInterface::class);

        $this->assertInstanceOf(PaymentMethodConfigurationInterface::class, $instance);
        $this->assertSame($this->paymentMethodConfiguration, $instance);
    }

    /**
     * @return void
     */
    public function test_FallbackModeConfigurationInterface_can_be_resolved()
    {
        $container = $this->buildContainer();

        $instance = $container->get(FallbackModeConfigurationInterface::class);

        $this->assertInstanceOf(FallbackModeConfigurationInterface::class, $instance);
        $this->assertSame($this->fallbackModeConfiguration, $instance);
    }

    /**
     * @return void
     */
    public function test_UserAgentInfoProviderInterface_can_be_resolved()
    {
        $container = $this->buildContainer();

        $instance = $container->get(UserAgentInfoProviderInterface::class);

        $this->assertInstanceOf(UserAgentInfoProviderInterface::class, $instance);
        $this->assertSame($this->userAgentInfoProvider, $instance);
    }

    /**
     * @return void
     */
    public function test_buildContainer_does_not_disable_certificate_validation()
    {
        $container = $this->buildContainer();

        /** @var Client */
        $client = $container->get(ClientInterface::class);

        $reflectedConfig = new \ReflectionProperty(Client::class, 'config');
        $reflectedConfig->setAccessible(true);
        /** @var array<string,mixed> */
        $config = $reflectedConfig->getValue($client);

        $this->assertArrayHasKey('verify', $config);
        $this->assertTrue($config['verify']);
    }

    const CLASS_MAP = [

        UserAgentFactory::class => [
            UserAgentFactory::class
        ],

        CheckoutClient::class => [
            CheckoutClientInterface::class
        ],

        CheckoutApiAdapter::class => [
            CheckoutApiInterface::class
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
    ];
}
