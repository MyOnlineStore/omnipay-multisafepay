<?php
/**
 * MultiSafepay XML Api Fetch Issuers Response.
 */

namespace Omnipay\MultiSafepay\Message;

use Omnipay\Common\Issuer;
use Omnipay\Common\Message\FetchIssuersResponseInterface;

/**
 * MultiSafepay XML Api Fetch Issuers Response.
 *
 * @deprecated This API is deprecated and will be removed in
 * an upcoming version of this package. Please switch to the Rest API.
 */
class FetchIssuersResponse extends AbstractResponse implements FetchIssuersResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return isset($this->data->issuers);
    }

    /**
     * Return available issuers as an associative array.
     *
     * @return Issuer[]
     */
    public function getIssuers()
    {
        $issuers = [];

        foreach ($this->data->issuers->issuer as $issuer) {
            $issuers[] = new Issuer(
                (string) $issuer->code,
                (string) $issuer->description
            );
        }

        return $issuers;
    }
}
