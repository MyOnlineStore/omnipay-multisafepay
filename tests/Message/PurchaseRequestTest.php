<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Tests\Message;

use MyOnlineStore\Omnipay\MultiSafepay\Item;
use MyOnlineStore\Omnipay\MultiSafepay\Message\PurchaseRequest;
use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

final class PurchaseRequestTest extends TestCase
{
    /** @var PurchaseRequest */
    private $request;

    protected function setUp(): void
    {
        $this->request = new PurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize(
            [
                'apiKey' => '123456789',
                'amount' => 10.00,
                'currency' => 'eur',
                'description' => 'Test transaction',
                'cancel_url' => 'http://localhost/cancel',
                'notify_url' => 'http://localhost/notify',
                'return_url' => 'http://localhost/return',
                'close_window' => false,
                'days_active' => 3,
                'send_mail' => true,
                'gateway' => 'IDEAL',
                'google_analytics_code' => '123456789',
                'manual' => false,
                'transactionId' => 'TEST-TRANS-1',
                'type' => 'redirect',
                'var1' => 'extra data 1',
                'var2' => 'extra data 2',
                'var3' => 'extra data 3',
                'items' => [
                    new Item(
                        [
                            'name' => 'Foo',
                            'description' => 'FooBar',
                            'quantity' => '1',
                            'price' => '5.95',
                        ]
                    ),
                ],
                'card' => new CreditCard(
                    [
                        'firstName'     => 'Example',
                        'lastName'      => 'Customer',
                        'number'        => '4222222222222222',
                        'expiryMonth'   => '01',
                        'expiryYear'    => '2020',
                        'cvv'           => '123',
                        'email'         => 'customer@example.com',
                        'phone'         => '0612345678',
                        'address1'      => '1 Scrubby Creek Road',
                        'country'       => 'AU',
                        'city'          => 'Scrubby Creek',
                        'postalcode'    => '4999',
                        'state'         => 'QLD',
                        'gender'        => 'F',
                        'birthday'      => '1970-07-10',
                    ]
                ),
            ]
        );
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertTrue($response->isRedirect());

        self::assertEquals(
            'https://testpay.multisafepay.com/pay/?order_id=TEST-TRANS-1',
            $response->getRedirectUrl()
        );

        self::assertEquals('TEST-TRANS-1', $response->getTransactionId());
    }

    public function testInvalidAmount(): void
    {
        $this->setMockHttpResponse('PurchaseInvalidAmount.txt');

        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertEquals('Invalid amount', $response->getMessage());
        self::assertEquals(1001, $response->getCode());
    }

    public function testDataIntegrity(): void
    {
        self::assertEquals('123456789', $this->request->getGoogleAnalyticsCode());
        self::assertEquals('EUR', $this->request->getCurrency());
        self::assertEquals('extra data 1', $this->request->getVar1());
        self::assertEquals('extra data 2', $this->request->getVar2());
        self::assertEquals('extra data 3', $this->request->getVar3());
        self::assertEquals('http://localhost/cancel', $this->request->getCancelUrl());
        self::assertEquals('http://localhost/notify', $this->request->getNotifyUrl());
        self::assertEquals('http://localhost/return', $this->request->getReturnUrl());
        self::assertEquals('IDEAL', $this->request->getGateway());
        self::assertEquals('redirect', $this->request->getType());
        self::assertEquals('Test transaction', $this->request->getDescription());
        self::assertEquals('TEST-TRANS-1', $this->request->getTransactionId());
        self::assertEquals(10.00, $this->request->getAmount());
        self::assertEquals(3, $this->request->getDaysActive());
        self::assertFalse($this->request->getCloseWindow());
        self::assertFalse($this->request->getManual());
        self::assertTrue($this->request->getSendMail());
    }
}
