<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay;

use MyOnlineStore\Omnipay\MultiSafepay\Message\AuthorizeRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\CancelOrderRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\CaptureRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\CompleteAuthorizeRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\CompletePurchaseRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\FetchIssuersRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\FetchPaymentMethodsRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\FetchTransactionRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\PurchaseRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\RefundRequest;
use Omnipay\Common\AbstractGateway;

/**
 * MultiSafepay REST Api gateway.
 *
 * This class forms the gateway class for the MultiSafepay REST API.
 *
 * The MultiSafepay REST api is the latest version of their API and uses
 * HTTP verbs and a RESTful endpoint structure. The response payloads are
 * formatted as JSON and authentication happens via an api key within
 * the HTTP headers.
 *
 * ### Environments
 *
 * The MultiSafepay API support two different environments. A sandbox environment
 * which can be used for testing purposes. And a live environment for production processing.
 *
 * ### Sandbox environment
 *
 * The sandbox environment allows the user to test their implementation, transactions
 * will be created but no actual money will be involved.
 *
 * To use the sandbox environment the testMode parameter needs to be set on the gateway object.
 * This ensures that the sandbox endpoint will be used, instead of the production endpoint.
 *
 * ### Credentials
 *
 * Before you can use the API you need to register an account with MultiSafepay.
 *
 * To request access to the sandbox environment you need to register
 * at https://testmerchant.multisafepay.com/signup
 *
 * To request access to the live environment you need to register
 * at https://merchant.multisafepay.com/signup
 *
 * After you create your account, you can access the MultiSafepay dashboard which is located at
 * https://testmerchant.multisafepay.com or https://merchant.multisafepay.com depending
 * on the environment you use.
 *
 * To obtain an API key you first need to register your website with MultiSafepay.
 * This can be done within several steps:
 *
 * 1. Navigate to the create page: https://merchant.multisafepay.com/sites
 * 2. Fill out the required fields.
 * 3. Click save.
 * 4. You will be redirect to the site details page where you can find the API key.
 *
 * ### Initialize gateway
 *
 * <code>
 *   // Create the gateway
 *   $gateway = Omnipay::create('MultiSafepay_Rest');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(array(
 *       'apiKey' => 'API-KEY',
 *       'locale' => 'en',
 *       'testMode' => true, // Or false, when you want to use the production environment
 *   ));
 * </code>
 *
 * ### Retrieve Payment Methods
 *
 * <code>
 *    $request = $gateway->fetchPaymentMethods();
 *    $response = $request->send();
 *    $paymentMethods = $response->getPaymentMethods();
 * </code>
 *
 * @link https://github.com/MultiSafepay/PHP
 * @link https://www.multisafepay.com/docs/getting-started/
 * @link https://www.multisafepay.com/documentation/doc/API-Reference/
 * @link https://www.multisafepay.com/documentation/doc/Step-by-Step/
 * @link https://www.multisafepay.com/signup/
 */
final class Gateway extends AbstractGateway
{
    public function getName(): string
    {
        return 'MultiSafepay REST';
    }

    /**
     * Get the gateway parameters
     *
     * @return mixed[]
     */
    public function getDefaultParameters(): array
    {
        return [
            'apiKey' => '',
            'locale' => 'en',
            'testMode' => false,
        ];
    }

    /**
     * Get the locale.
     *
     * Optional ISO 639-1 language code which is used to specify a
     * a language used to display gateway information and other
     * messages in the responses.
     *
     * The default language is English.
     */
    public function getLocale(): ?string
    {
        return $this->getParameter('locale');
    }

    /**
     * Set the locale.
     *
     * Optional ISO 639-1 language code which is used to specify a
     * a language used to display gateway information and other
     * messages in the responses.
     *
     * The default language is English.
     */
    public function setLocale(string $value): self
    {
        return $this->setParameter('locale', $value);
    }

    /**
     * Get the gateway API Key
     *
     * Authentication is by means of a single secret API key set as
     * the apiKey parameter when creating the gateway object.
     */
    public function getApiKey(): string
    {
        return $this->getParameter('apiKey');
    }

    /**
     * Set the gateway API Key
     *
     * Authentication is by means of a single secret API key set as
     * the apiKey parameter when creating the gateway object.
     *
     * @return Gateway provides a fluent interface.
     */
    public function setApiKey(string $value): Gateway
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * @param mixed[] $parameters
     */
    public function authorize(array $parameters = []): AuthorizeRequest
    {
        $request = $this->createRequest(AuthorizeRequest::class, $parameters);
        \assert($request instanceof AuthorizeRequest);

        return $request;
    }

    /**
     * @param mixed[] $parameters
     */
    public function capture(array $parameters = []): CaptureRequest
    {
        $request = $this->createRequest(CaptureRequest::class, $parameters);
        \assert($request instanceof CaptureRequest);

        return $request;
    }

    /**
     * @param mixed[] $parameters
     */
    public function completeAuthorize(array $parameters = []): CompleteAuthorizeRequest
    {
        $request = $this->createRequest(CompleteAuthorizeRequest::class, $parameters);
        \assert($request instanceof CompleteAuthorizeRequest);

        return $request;
    }

    /**
     * @param mixed[] $parameters
     */
    public function completePurchase(array $parameters = []): CompletePurchaseRequest
    {
        $request = $this->createRequest(CompletePurchaseRequest::class, $parameters);
        \assert($request instanceof CompletePurchaseRequest);

        return $request;
    }

    /**
     * @param mixed[] $parameters
     */
    public function fetchIssuers(array $parameters = []): FetchIssuersRequest
    {
        $request = $this->createRequest(FetchIssuersRequest::class, $parameters);
        \assert($request instanceof FetchIssuersRequest);

        return $request;
    }

    /**
     * @param mixed[] $parameters
     */
    public function fetchPaymentMethods(array $parameters = []): FetchPaymentMethodsRequest
    {
        $request = $this->createRequest(FetchPaymentMethodsRequest::class, $parameters);
        \assert($request instanceof FetchPaymentMethodsRequest);

        return $request;
    }

    /**
     * @param mixed[] $parameters
     */
    public function fetchTransaction(array $parameters = []): FetchTransactionRequest
    {
        $request = $this->createRequest(FetchTransactionRequest::class, $parameters);
        \assert($request instanceof FetchTransactionRequest);

        return $request;
    }

    /**
     * @param mixed[] $parameters
     */
    public function purchase(array $parameters = []): PurchaseRequest
    {
        $request = $this->createRequest(PurchaseRequest::class, $parameters);
        \assert($request instanceof PurchaseRequest);

        return $request;
    }

    /**
     * @param mixed[] $parameters
     */
    public function refund(array $parameters = []): RefundRequest
    {
        $request = $this->createRequest(RefundRequest::class, $parameters);
        \assert($request instanceof RefundRequest);

        return $request;
    }

    /**
     * @param mixed[] $parameters
     */
    public function void(array $parameters = []): CancelOrderRequest
    {
        $request = $this->createRequest(CancelOrderRequest::class, $parameters);
        \assert($request instanceof CancelOrderRequest);

        return $request;
    }
}
