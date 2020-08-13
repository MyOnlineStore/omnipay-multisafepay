<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Tests;

use MyOnlineStore\Omnipay\MultiSafepay\Gateway;
use Omnipay\Tests\GatewayTestCase;

final class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    protected $gateway;

    protected function setUp(): void
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setApiKey('123456789');
    }

    public function testAuthorizeRequest(): void
    {
        $request = $this->gateway->authorize(['amount' => 10.00]);

        self::assertEquals(10.00, $request->getAmount());
    }

    public function testCapture(): void
    {
        $request = $this->gateway->capture(['transactionId' => 'barfoo']);

        self::assertEquals('barfoo', $request->getTransactionId());
    }

    public function testCompleteAuthorizeRequest(): void
    {
        $request = $this->gateway->completeAuthorize(['amount' => 10.00]);

        self::assertEquals(10.00, $request->getAmount());
    }

    public function testCompletePurchaseRequest(): void
    {
        $request = $this->gateway->completePurchase(['amount' => 10.00]);

        self::assertEquals(10.00, $request->getAmount());
    }

    public function testFetchIssuersRequest(): void
    {
        $request = $this->gateway->fetchIssuers(['paymentMethod' => 'ideal']);

        self::assertEquals('ideal', $request->getPaymentMethod());
    }

    public function testFetchPaymentMethodsRequest(): void
    {
        $request = $this->gateway->fetchPaymentMethods(['country' => 'NL']);

        self::assertEquals('NL', $request->getCountry());
    }

    public function testFetchTransaction(): void
    {
        $request = $this->gateway->fetchTransaction(['transactionId' => 'barfoo']);

        self::assertEquals('barfoo', $request->getTransactionId());
    }

    public function testPurchaseRequest(): void
    {
        $request = $this->gateway->purchase(['amount' => 10.00]);

        self::assertEquals(10.00, $request->getAmount());
    }

    public function testVoid(): void
    {
        $request = $this->gateway->void(['transactionId' => 'barfoo']);

        self::assertEquals('barfoo', $request->getTransactionId());
    }
}
