<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Tests\Message;

use MyOnlineStore\Omnipay\MultiSafepay\Message\CancelOrderRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\CancelOrderResponse;
use Omnipay\Tests\TestCase;

final class CancelOrderRequestTest extends TestCase
{
    /** @var CancelOrderRequest */
    private $request;

    protected function setUp(): void
    {
        $this->request = new CancelOrderRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize(
            [
                'api_key' => '123456789',
                'transactionId' => 'qux',
            ]
        );
    }

    public function testSendFailure(): void
    {
        $this->setMockHttpResponse('CancelOrderFailure.txt');

        $response = $this->request->send();

        self::assertFalse($response->isRedirect());
        self::assertInstanceOf(CancelOrderResponse::class, $response);

        self::assertFalse($response->isSuccessful());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CancelOrderSuccess.txt');

        $response = $this->request->send();

        self::assertFalse($response->isRedirect());
        self::assertInstanceOf(CancelOrderResponse::class, $response);

        self::assertTrue($response->isSuccessful());
    }
}
