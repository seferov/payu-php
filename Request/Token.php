<?php

namespace Seferov\PayU\Request;

/**
 * Class Token.
 *
 * @author Farhad Safarov <farhad.safarov@gmail.com>
 */
class Token extends AbstractRequest
{
    const ENDPOINT = 'https://secure.payu.com.tr/order/tokens/';

    public function getEndpoint()
    {
        return self::ENDPOINT;
    }

    /**
     * Creates a new sale using the token.
     *
     * @param string    $code           The token number
     * @param float|int $amount         New order amount
     * @param string    $currency       Price currency
     * @param string    $externalRef    Merchant Reference Number for the transaction
     * @param array     $additionalData
     *
     * @return Token
     */
    public function newSale($code, $amount, $currency, $externalRef, array $additionalData = [])
    {
        $data = [
            'REF_NO' => $code,
            'METHOD' => 'TOKEN_NEWSALE',
            'AMOUNT' => $amount,
            'CURRENCY' => $currency,
            'EXTERNAL_REF' => $externalRef,
        ];

        $this->setRequestParams(array_merge($data, $additionalData));
        
        return $this;
    }

    /**
     * Get info about the token.
     *
     * @param string $code The token number
     *
     * @return Token
     */
    public function getInfo($code)
    {
        $this->setRequestParams([
            'REF_NO' => $code,
            'METHOD' => 'TOKEN_GETINFO',
        ]);

        return $this;
    }

    /**
     * Cancel the token.
     *
     * @param string $code   The token number
     * @param string $reason Reason for cancelling the order
     *
     * @return Token
     */
    public function cancel($code, $reason)
    {
        $this->setRequestParams([
            'REF_NO' => $code,
            'METHOD' => 'TOKEN_CANCEL',
            'CANCEL_REASON' => $reason,
        ]);

        return $this;
    }

    /**
     * Persist the token.
     *
     * @param string $code The token number
     * 
     * @return Token
     */
    public function persist($code)
    {
        $this->setRequestParams([
            'REF_NO' => $code,
            'METHOD' => 'TOKEN_PERSIST',
        ]);

        return $this;
    }
}
