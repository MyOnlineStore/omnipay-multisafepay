<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Tests\Message;

use MyOnlineStore\Omnipay\MultiSafepay\Message\FetchPaymentMethodsRequest;
use Omnipay\Common\PaymentMethod;
use Omnipay\Tests\TestCase;

final class FetchPaymentMethodsRequestTest extends TestCase
{
    /** @var FetchPaymentMethodsRequest */
    private $request;

    protected function setUp(): void
    {
        $this->request = new FetchPaymentMethodsRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize(
            [
                'api_key' => '123456789',
                'country' => 'NL',
            ]
        );
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('FetchPaymentMethodsSuccess.txt');

        $response = $this->request->send();

        $paymentMethods = $response->getPaymentMethods();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertNull($response->getTransactionReference());

        self::assertIsArray($paymentMethods);
        self::assertContainsOnlyInstancesOf(PaymentMethod::class, $paymentMethods);
    }
}
