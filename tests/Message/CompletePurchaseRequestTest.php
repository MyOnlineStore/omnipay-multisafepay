<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Tests\Message;

use MyOnlineStore\Omnipay\MultiSafepay\Message\CompletePurchaseRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;

final class CompletePurchaseRequestTest extends TestCase
{
    /** @var CompletePurchaseRequest */
    private $request;

    protected function setUp(): void
    {
        $this->request = new CompletePurchaseRequest(
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
        $this->setMockHttpResponse('CompletePurchaseFailure.txt');

        $response = $this->request->send();

        self::assertFalse($response->isRedirect());
        self::assertInstanceOf(CompletePurchaseResponse::class, $response);

        self::assertFalse($response->isSuccessful());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');

        $response = $this->request->send();

        self::assertFalse($response->isRedirect());
        self::assertInstanceOf(CompletePurchaseResponse::class, $response);

        self::assertTrue($response->isSuccessful());
    }
}
