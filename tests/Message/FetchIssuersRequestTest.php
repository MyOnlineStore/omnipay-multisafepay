<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Tests\Message;

use MyOnlineStore\Omnipay\MultiSafepay\Message\FetchIssuersRequest;
use MyOnlineStore\Omnipay\MultiSafepay\Message\FetchIssuersResponse;
use Omnipay\Common\Issuer;
use Omnipay\Tests\TestCase;

final class FetchIssuersRequestTest extends TestCase
{
    /** @var FetchIssuersRequest */
    private $request;

    protected function setUp(): void
    {
        $this->request = new FetchIssuersRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize(
            [
                'api_key' => '123456789',
                'paymentMethod' => 'IDEAL',
            ]
        );
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('FetchIssuersSuccess.txt');

        $response = $this->request->send();

        $issuers = $response->getIssuers();

        self::assertContainsOnlyInstancesOf(Issuer::class, $issuers);
        self::assertFalse($response->isRedirect());
        self::assertInstanceOf(FetchIssuersResponse::class, $response);
        self::assertIsArray($issuers);

        self::assertNull($response->getTransactionReference());
        self::assertTrue($response->isSuccessful());
    }

    public function testIssuerNotFound(): void
    {
        $this->setMockHttpResponse('FetchIssuersFailure.txt');

        $response = $this->request->send();

        self::assertEquals('Not found', $response->getMessage());
        self::assertEquals(404, $response->getCode());
        self::assertFalse($response->isRedirect());

        self::assertFalse($response->isSuccessful());
        self::assertInstanceOf(FetchIssuersResponse::class, $response);
    }
}
