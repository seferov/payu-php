<?php

namespace Seferov\PayU;

use Seferov\PayU\Request\RequestInterface;

/**
 * Class Client.
 *
 * @author Farhad Safarov <farhad.safarov@gmail.com>
 */
class Client
{
    /**
     * @var string
     */
    private $merchantCode;

    /**
     * @var string
     */
    private $merchantKey;

    public function __construct($merchantCode, $merchantKey)
    {
        $this->merchantCode = $merchantCode;
        $this->merchantKey = $merchantKey;
    }

    /**
     * @param RequestInterface $request
     *
     * @return array
     */
    public function request(RequestInterface $request)
    {
        $request->config($this->merchantCode, $this->merchantKey);

        return $request->request()->getResponse();
    }
}
