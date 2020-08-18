<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Tests\Message;

use MyOnlineStore\Omnipay\MultiSafepay\Message\RefundRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\RefundResponse;
use Omnipay\Tests\TestCase;

final class RefundRequestTest extends TestCase
{
    /** @var RefundRequest */
    private $request;

    protected function setUp(): void
    {
        $this->request = new RefundRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize(
            [
                'api_key' => '123456789',
                'transactionId' => '3326965',
                'amount' => 10,
                'currency' => 'EUR',
                'description' => 'Refund qux',
            ]
        );
    }

    public function testSendFailure(): void
    {
        $this->setMockHttpResponse('RefundFailure.txt');

        $response = $this->request->send();

        self::assertFalse($response->isRedirect());
        self::assertInstanceOf(RefundResponse::class, $response);

        self::assertFalse($response->isSuccessful());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('RefundSuccess.txt');

        $response = $this->request->send();

        self::assertFalse($response->isRedirect());
        self::assertInstanceOf(RefundResponse::class, $response);

        self::assertTrue($response->isSuccessful());
    }
}
