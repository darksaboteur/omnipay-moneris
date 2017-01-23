<?php

namespace Omnipay\MonerisCA;

use Omnipay\Common\AbstractGateway;

/**
 * Moneris CA XML Class
 *
 * @link https://developer.moneris.com/Documentation/NA/E-Commerce%20Solutions/API/Purchase?lang=php
 */
class Gateway extends AbstractGateway {
    /**
     * Get name
     *
     * @access public
     * @return string
     */
    public function getName() {
        return 'MonerisCA';
    }

    /**
     * Get default parameters
     *
     * @access public
     * @return array
     */
    public function getDefaultParameters() {
        return [
            'merchant'     => '',
            'password'     => '',
            'testMode'     => false,
            'userAgentHeader' => 'PHP NA - 1.0.5',
        ];
    }

    /**
     * Get merchant
     *
     * @access public
     * @return string
     */
    public function getMerchant() {
        return $this->getParameter('merchant');
    }

    /**
     * Set merchant
     *
     * @param string $value Merchant value
     *
     * @access public
     * @return void
     */
    public function setMerchant($value) {
        return $this->setParameter('merchant', $value);
    }

    /**
     * Get password
     *
     * @access public
     * @return string
     */
    public function getPassword() {
        return $this->getParameter('password');
    }

    /**
     * Set password
     *
     * @param string $value Password value
     *
     * @access public
     * @return void
     */
    public function setPassword($value) {
        return $this->setParameter('password', $value);
    }

    /**
     * Get user agent header
     *
     * @access public
     * @return string
     */
    public function getUserAgentHeader() {
        return $this->getParameter('userAgentHeader');
    }

    /**
     * Set user agent header
     *
     * @param string $value User agent header value
     *
     * @access public
     * @return void
     */
    public function setUserAgentHeader($value) {
        return $this->setParameter('userAgentHeader', $value);
    }

    /**
     * Purchase
     *
     * @param array $parameters Parameters
     *
     * @access public
     * @return \Omnipay\WorldPayXML\Message\PurchaseRequest
     */
    public function purchase(array $parameters = []) {
        return $this->createRequest('\Omnipay\MonerisCA\Message\PurchaseRequest', $parameters);
    }
}
