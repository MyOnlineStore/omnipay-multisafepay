<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * MultiSafepay Rest Api Refund Request.
 *
 * The MultiSafepay API support refunds, meaning you can refund any
 * transaction to the customer. The fund will be deducted
 * from the MultiSafepay balance.
 *
 * The API also support partial refunds which means that only a
 * part of the amount will be refunded.
 *
 * When a transaction has been refunded the status will change to
 * "refunded". When the transaction has only been partial refunded the
 * status will change to "partial_refunded".
 *
 * ### Example
 *
 * <code>
 *    $request = $this->gateway->refund();
 *
 *    $request->setTransactionId('test-transaction');
 *    $request->setAmount('10.00');
 *    $request->setCurrency('eur');
 *    $request->setDescription('Test Refund');
 *
 *    $response = $request->send();
 *    var_dump($response->isSuccessful());
 * </code>
 */
final class RefundRequest extends Request
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
        $this->validate('amount', 'currency', 'description', 'transactionId');

        return [
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency(),
            'description' => $this->getDescription(),
            'id' => $this->getTransactionId(),
            'type' => 'refund',
        ];
    }

    /**
     * Send the request with specified data
     *
     * @param mixed $data
     *
     * @throws InvalidRequestException
     */
    public function sendData($data): RefundResponse
    {
        $httpResponse = $this->sendRequest(
            'POST',
            '/orders/' . $data['id'] . '/refunds',
            \json_encode($data)
        );

        $this->response = new RefundResponse(
            $this,
            \json_decode($httpResponse->getBody()->getContents(), true)
        );

        return $this->response;
    }
}
