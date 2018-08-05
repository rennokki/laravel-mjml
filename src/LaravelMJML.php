<?php

namespace Rennokki\LaravelMJML;

use GuzzleHttp\Client as GuzzleClient;

class LaravelMJML
{
    private $secretKey;
    protected $appId;
    public $publicKey;

    protected $client;
    protected $mustache;

    public static $apiEndpoint = 'https://api.mjml.io/v1';

    public function __construct()
    {
        $this->client = new GuzzleClient();
        $this->mustache = new \Mustache_Engine;
    }

    /**
     * Set the public key.
     *
     * @param string $publicKey
     * @return void
     */
    public function setPublicKey(string $publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Set the secret key.
     *
     * @param string $secretKey
     * @return void
     */
    public function setSecretKey(string $secretKey)
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    /**
     * Set the App ID.
     *
     * @param string $appId
     * @return void
     */
    public function setAppId(string $appId)
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * Getting the request response (in JSON) for rendering MJML.
     *
     * @param string $mjml
     * @return string
     */
    public function renderRequest(string $mjml)
    {
        try {
            $request = $this->client->request('POST', Self::$apiEndpoint.'/render', [
                'auth' => [$this->appId, $this->secretKey],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accepts' => 'application/json',
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'mjml' => $mjml,
                ],
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }

        return json_decode($request->getBody());
    }

    /**
     * Getting the rendered HTML for a specified MJML.
     *
     * @param string $mjml
     * @return string|null
     */
    public function render(string $mjml)
    {
        $request = $this->renderRequest($mjml);

        if (property_exists($request, 'status_code') && $request->status_code != 200) {
            return;
        }

        return $request->html;
    }

    /**
     * Rendering the MJML given and apllying mustache render on it.
     *
     * @param string $mjml
     * @param array $parameters
     * @return string|null
     */
    public function renderWithMustache(string $mjml, array $parameters = [])
    {
        $html = $this->render($mjml);

        return $this->mustache->render($html, $parameters);
    }
}
