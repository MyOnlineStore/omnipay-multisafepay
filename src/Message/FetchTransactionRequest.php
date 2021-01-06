<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * MultiSafepay Rest Api Fetch Transaction Request.
 *
 * To get information about a previous processed transaction, MultiSafepay provides
 * the /orders/{order_id} resource. This resource can be used to query the details
 * about a specific transaction.
 *
 * <code>
 *   // Fetch the transaction.
 *   $transaction = $gateway->fetchTransaction();
 *   $transaction->setTransactionId($transactionId);
 *   $response = $transaction->send();
 *   print_r($response->getData());
 * </code>
 *
 * @link https://www.multisafepay.com/documentation/doc/API-Reference
 */
final class FetchTransactionRequest extends Request
{
    /**
     * Get the required data which is needed
     * to execute the API request.
     *
     * @return mixed[]
     *
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionId');

        return ['transactionId' => $this->getTransactionId()];
    }

    /**
     * Execute the API request.
     *
     * @param mixed $data
     *
     * @throws InvalidRequestException
     */
    public function sendData($data): FetchTransactionResponse
    {
        $httpResponse = $this->sendRequest(
            'get',
            '/orders/' . $data['transactionId']
        );

        $this->response = new FetchTransactionResponse(
            $this,
            \json_decode($httpResponse->getBody()->getContents(), true)
        );

        return $this->response;
    }
}
