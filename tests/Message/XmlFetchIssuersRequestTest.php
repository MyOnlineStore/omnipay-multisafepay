<?php

namespace Omnipay\MultiSafepay\Message;

use Omnipay\Common\Issuer;
use Omnipay\Tests\TestCase;

class XmlFetchIssuersRequestTest extends TestCase
{
    /**
     * @var FetchIssuersRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new FetchIssuersRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize(array(
            'accountId' => '111111',
            'siteId' => '222222',
            'siteCode' => '333333',
        ));
    }

    /**
     * @dataProvider issuersProvider
     */
    public function testSendSuccess($expected)
    {
        $this->setMockHttpResponse('XmlFetchIssuersSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($expected, $response->getIssuers());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('XmlFetchIssuersFailure.txt');

        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Invalid merchant security code', $response->getMessage());
        $this->assertEquals(1005, $response->getCode());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetData($xml)
    {
        $data = $this->request->getData();
        $this->assertInstanceOf('SimpleXMLElement', $data);

        // Just so the provider remains readable...
        $dom = dom_import_simplexml($data)->ownerDocument;
        $dom->formatOutput = true;
        $this->assertEquals($xml, $dom->saveXML());
    }

    public function issuersProvider()
    {
        return [
            [
                [
                    new Issuer('0031', 'ABN AMRO'),
                    new Issuer('0751', 'SNS Bank'),
                    new Issuer('0721', 'ING'),
                    new Issuer('0021', 'Rabobank'),
                    new Issuer('0091', 'Friesland Bank'),
                    new Issuer('0761', 'ASN Bank'),
                    new Issuer('0771', 'SNS Regio Bank'),
                    new Issuer('0511', 'Triodos Bank'),
                    new Issuer('0161', 'Van Lanschot Bankiers'),
                    new Issuer('0801', 'Knab'),
                ],
            ],
        ];
    }

    public function dataProvider()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<idealissuers ua="Omnipay">
  <merchant>
    <account>111111</account>
    <site_id>222222</site_id>
    <site_secure_code>333333</site_secure_code>
  </merchant>
</idealissuers>

EOF;

        return array(
            array($xml),
        );
    }
}
