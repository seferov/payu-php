<?php

namespace Seferov\PayU\Request;

/**
 * Interface RequestInterface.
 *
 * @author Farhad Safarov <farhad.safarov@gmail.com>
 */
interface RequestInterface
{
    /**
     * @param $merchantCode
     * @param $merchantKey
     */
    public function config($merchantCode, $merchantKey);

    /**
     * @return string
     */
    public function getEndpoint();

    /**
     * @return array
     */
    public function getResponse();

    /**
     * @param array
     * 
     * @return RequestInterface
     */
    public function request(array $params = []);
}
