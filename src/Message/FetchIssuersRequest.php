<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * MultiSafepay Rest Api Fetch Issuers Request.
 *
 * Some payment providers require you to specify a issuer.
 * This request provides a list of all possible issuers
 * for the specified gateway.
 *
 * Currently IDEAL is the only provider which requires an issuer.
 *
 * <code>
 *   $request = $gateway->fetchIssuers();
 *   $request->setPaymentMethod('IDEAL');
 *   $response = $request->send();
 *   $issuers = $response->getIssuers();
 *   print_r($issuers);
 * </code>
 *
 * @link https://www.multisafepay.com/documentation/doc/API-Reference
 */
final class FetchIssuersRequest extends Request
{
    /**
     * Get the required data for the API request.
     *
     * @return mixed[]
     *
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('paymentMethod');

        return ['paymentMethod' => $this->getPaymentMethod()];
    }

    /**
     * Execute the API request.
     *
     * @param mixed $data
     *
     * @throws InvalidRequestException
     */
    public function sendData($data): FetchIssuersResponse
    {
        $httpResponse = $this->sendRequest(
            'GET',
            '/issuers/'.$data['paymentMethod']
        );

        $this->response = new FetchIssuersResponse(
            $this,
            \json_decode($httpResponse->getBody()->getContents(), true)
        );

        return $this->response;
    }
}
