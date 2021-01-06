<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\MultiSafepay\Message;

use MyOnlineStore\Omnipay\MultiSafepay\Item;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ItemBag;
use Omnipay\Common\Message\AbstractRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * MultiSafepay Rest API Abstract Request class.
 *
 * All Request classes prefixed by the Rest keyword
 * inheritance from this class.
 */
abstract class Request extends AbstractRequest
{
    /**
     * User Agent.
     *
     * This user agent will be sent with each API request.
     *
     * @var string
     */
    protected $userAgent = 'Omnipay';

    /**
     * Live API endpoint.
     *
     * This endpoint will be used when the test mode is disabled.
     *
     * @var string
     */
    protected $liveEndpoint = 'https://api.multisafepay.com/v1/json';

    /**
     * Test API endpoint.
     *
     * This endpoint will be used when the test mode is enabled.
     *
     * @var string
     */
    protected $testEndpoint = 'https://testapi.multisafepay.com/v1/json';

    /**
     * Get the locale.
     *
     * Optional ISO 639-1 language code which is used to specify a
     * a language used to display gateway information and other
     * messages in the responses.
     *
     * The default language is English.
     */
    public function getLocale(): ?string
    {
        return $this->getParameter('locale');
    }

    /**
     * Set the locale.
     *
     * Optional ISO 639-1 language code which is used to specify a
     * a language used to display gateway information and other
     * messages in the responses.
     *
     * The default language is English.
     */
    public function setLocale(string $value): Request
    {
        return $this->setParameter('locale', $value);
    }

    /**
     * Get the gateway API Key
     *
     * Authentication is by means of a single secret API key set as
     * the apiKey parameter when creating the gateway object.
     */
    public function getApiKey(): ?string
    {
        return $this->getParameter('apiKey');
    }

    /**
     * Set the gateway API Key
     *
     * Authentication is by means of a single secret API key set as
     * the apiKey parameter when creating the gateway object.
     */
    public function setApiKey(string $value): Request
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * @param Item[]|ItemBag|mixed[] $items
     *
     * @return static
     */
    public function setItems($items): self
    {
        $validatedItems = [];

        foreach ($items as $item) {
            if (\is_array($item)) {
                $item = new Item($item);
            }

            if (!$item instanceof Item) {
                throw new \InvalidArgumentException('Invalid item given');
            }

            $validatedItems[] = $item;
        }

        return parent::setItems($validatedItems);
    }

    public function getEndpoint(): string
    {
        if ($this->getTestMode()) {
            return $this->testEndpoint;
        }

        return $this->liveEndpoint;
    }

    /**
     * @return mixed[]
     *
     * @throws InvalidRequestException
     */
    protected function getHeaders(): array
    {
        $this->validate('apiKey');

        return [
            'api_key' => $this->getApiKey(),
            'User-Agent' => $this->userAgent,
        ];
    }

    /**
     * Execute the Guzzle request.
     *
     * @param mixed|null $data
     *
     * @throws InvalidRequestException
     */
    protected function sendRequest(string $method, string $endpoint, $data = null): ResponseInterface
    {
        return $this->httpClient->request(
            $method,
            $this->getEndpoint() . $endpoint,
            $this->getHeaders(),
            $data
        );
    }
}
