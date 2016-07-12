<?php

namespace Seferov\PayU\Request;

/**
 * Class AbstractRequest.
 * 
 * @author Farhad Safarov <farhad.safarov@gmail.com>
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * @var resource
     */
    private $curl;

    protected $merchantCode;

    protected $merchantKey;

    /**
     * @var array
     */
    private $requestParams = [];

    /**
     * @var array
     */
    private $response;

    public function config($merchantCode, $merchantKey)
    {
        $this->merchantCode = $merchantCode;
        $this->merchantKey = $merchantKey;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param array $params
     */
    public function setRequestParams(array $params)
    {
        $this->requestParams = $params;
    }

    /**
     * Executes the request to PayU service.
     *
     * @param array $params
     *
     * @return RequestInterface
     *
     * @throws \Exception
     */
    public function request(array $params = [])
    {
        $result = $this->runCurl($this->getEndpoint(), array_merge($this->requestParams, $params));

        $this->decodeResponse($result);

        return $this;
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @throws \Exception
     *
     * @return resource
     */
    private function runCurl($url, array $params)
    {
        if (!is_resource($this->curl)) {
            $this->curl = curl_init($url);

            curl_setopt_array(
                $this->curl,
                array(
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_FOLLOWLOCATION => false,
                    CURLOPT_USERAGENT => __CLASS__,
                    CURLOPT_POST => true,
                )
            );
        }

        $params = array_merge(
            $params,
            array(
                'MERCHANT' => $this->merchantCode,
                'TIMESTAMP' => gmdate('YmdHis'),
            )
        );

        unset($params['SIGN']);

        $params['SIGN'] = $this->calculateSignature($params);

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);

        $result = curl_exec($this->curl);

        if (false === $result) {
            throw new \Exception('Curl failed: '.curl_error($this->curl));
        }

        $httpCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        curl_close($this->curl);

        if (200 != $httpCode) {
            throw new \Exception('Unexpected HTTP code: '.$httpCode);
        }

        return $result;
    }

    /**
     * Calculates the signature.
     *
     * @param array $params
     *
     * @return string
     */
    private function calculateSignature(array $params)
    {
        ksort($params);
        $hashString = '';

        foreach ($params as $v) {
            $hashString .= strlen($v).$v;
        }

        return hash_hmac('md5', $hashString, $this->merchantKey);
    }

    /**
     * @param string $result
     *
     * @return array
     *
     * @throws \Exception
     */
    private function decodeResponse($result)
    {
        $response = json_decode($result, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception('JSON decode error');
        }

        $this->response = $response;
    }

    /**
     * Frees the resource when the object is destroyed.
     */
    public function __destruct()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }
}
