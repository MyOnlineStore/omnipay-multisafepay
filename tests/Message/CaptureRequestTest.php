<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Tests\Message;

use MyOnlineStore\Omnipay\MultiSafepay\Message\CaptureRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\CaptureResponse;
use Omnipay\Tests\TestCase;

final class CaptureRequestTest extends TestCase
{
    /** @var CaptureRequest */
    private $request;

    protected function setUp(): void
    {
        $this->request = new CaptureRequest(
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
        $this->setMockHttpResponse('CaptureFailure.txt');

        $response = $this->request->send();

        self::assertFalse($response->isRedirect());
        self::assertInstanceOf(CaptureResponse::class, $response);

        self::assertFalse($response->isSuccessful());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');

        $response = $this->request->send();

        self::assertFalse($response->isRedirect());
        self::assertInstanceOf(CaptureResponse::class, $response);

        self::assertTrue($response->isSuccessful());
    }
}
