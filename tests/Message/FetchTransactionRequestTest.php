<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Tests\Message;

use MyOnlineStore\Omnipay\MultiSafepay\Message\FetchTransactionRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\FetchTransactionResponse;
use Omnipay\Tests\TestCase;

final class FetchTransactionRequestTest extends TestCase
{
    /** @var FetchTransactionRequest */
    private $request;

    protected function setUp(): void
    {
        $this->request = new FetchTransactionRequest(
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
        $this->setMockHttpResponse('FetchTransactionFailure.txt');

        $response = $this->request->send();

        self::assertFalse($response->isRedirect());
        self::assertInstanceOf(FetchTransactionResponse::class, $response);

        self::assertFalse($response->isSuccessful());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');

        $response = $this->request->send();

        self::assertFalse($response->isRedirect());
        self::assertInstanceOf(FetchTransactionResponse::class, $response);

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isInitialized());
        self::assertFalse($response->isCancelled());
        self::assertFalse($response->isUncleared());
        self::assertFalse($response->isDeclined());
        self::assertFalse($response->isRefunded());
        self::assertFalse($response->isExpired());
    }
}
