<?php
declare(strict_types=1);

namespace Message;

use Omnipay\MultiSafepay\Message\RestCancelOrderRequest;
use Omnipay\MultiSafepay\Message\RestCancelOrderResponse;
use Omnipay\Tests\TestCase;

final class RestCancelOrderRequestTest extends TestCase
{
    /**
     * @var RestCancelOrderRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new RestCancelOrderRequest(
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

    public function testSendFailure()
    {
        $this->setMockHttpResponse('RestCancelOrderFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isRedirect());
        $this->assertInstanceOf(RestCancelOrderResponse::class, $response);

        $this->assertFalse($response->isSuccessful());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RestCancelOrderSuccess.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isRedirect());
        $this->assertInstanceOf(RestCancelOrderResponse::class, $response);

        $this->assertTrue($response->isSuccessful());
    }
}
