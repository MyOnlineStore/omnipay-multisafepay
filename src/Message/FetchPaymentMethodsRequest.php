<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

/**
 * MultiSafepay Rest Api Fetch Payments Methods Request.
 *
 * The MultiSafepay API supports multiple payment gateways, such as
 * iDEAL, Paypal or CreditCard. This request provides a list
 * of all supported payment methods.
 *
 * ### Example
 *
 * <code>
 *    $request = $gateway->fetchPaymentMethods();
 *    $response = $request->send();
 *    $paymentMethods = $response->getPaymentMethods();
 *    print_r($paymentMethods);
 * </code>
 *
 * @link https://www.multisafepay.com/documentation/doc/API-Reference
 */
final class FetchPaymentMethodsRequest extends Request
{
    /**
     * Get the country.
     */
    public function getCountry(): ?string
    {
        return $this->getParameter('country');
    }

    /**
     * Set the country.
     */
    public function setCountry(string $value): AbstractRequest
    {
        return $this->setParameter('country', $value);
    }

    /**
     * Get the required data for the API request.
     *
     * @return mixed[]
     */
    public function getData(): array
    {
        return \array_filter(
            [
                'amount' => $this->getAmountInteger(),
                'country' => $this->getCountry(),
                'currency' => $this->getCurrency(),
            ]
        );
    }

    /**
     * Execute the API request.
     *
     * @param mixed $data
     *
     * @throws InvalidRequestException
     */
    public function sendData($data): FetchPaymentMethodsResponse
    {
        $httpResponse = $this->sendRequest('GET', '/gateways', \json_encode($data));

        $this->response = new FetchPaymentMethodsResponse(
            $this,
            \json_decode($httpResponse->getBody()->getContents(), true)
        );

        return $this->response;
    }
}
