<?php

namespace Omnipay\MonerisCA\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Omnipay WorldPay XML Purchase Request
 */
class PurchaseRequest extends AbstractRequest {
    const EP_HOST_LIVE = 'https://www3.moneris.com';
    const EP_HOST_TEST = 'https://esqa.moneris.com';

    const EP_PATH = '/gateway2/servlet/MpgRequest';

    /**
     * Get accept header
     *
     * @access public
     * @return string
     */
    public function getAcceptHeader() {
        return $this->getParameter('acceptHeader');
    }

    /**
     * Set accept header
     *
     * @param string $value Accept header
     *
     * @access public
     * @return void
     */
    public function setAcceptHeader($value) {
        return $this->setParameter('acceptHeader', $value);
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
     * @param string $value Merchant
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
     * @param string $value Password
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
     * @param string $value User agent header
     *
     * @access public
     * @return void
     */
    public function setUserAgentHeader($value) {
        return $this->setParameter('userAgentHeader', $value);
    }

    /**
     * Get data
     *
     * @access public
     * @return \SimpleXMLElement
     */
    public function getData() {
        $this->validate('amount', 'card');
        $this->getCard()->validate();

        $data = new \SimpleXMLElement('<request />');

        $merchant = $data->addChild('store_id', $this->getMerchant());
        $password = $data->addChild('api_token', $this->getPassword());

        $purchase = $data->addChild('purchase');

        $purchase->addChild('order_id', $this->getTransactionId());
        //$purchase->addChild('cust_id', $this->getCustomerId());
        $purchase->addChild('amount', $this->getAmount());
        $purchase->addChild('pan', $this->getCard()->getNumber());
        $purchase->addChild('expdate', $this->getCard()->getExpiryDate('Y').$this->getCard()->getExpiryDate('m'));
        $purchase->addChild('crypt_type', 7);
        //$purchase->addChild('dynamic_descriptor', '');

        return $data;
    }

    /**
     * Send data
     *
     * @param \SimpleXMLElement $data Data
     *
     * @access public
     * @return RedirectResponse
     */
    public function sendData($data) {
        $implementation = new \DOMImplementation();

        $document = $implementation->createDocument(null, '');
        $document->encoding = 'utf-8';

        $node = $document->importNode(dom_import_simplexml($data), true);
        $document->appendChild($node);

        $headers = [
            'Content-Type'  => 'text/xml; charset=utf-8'
        ];


        $xml = $document->saveXML();


        $httpResponse = $this->httpClient
            ->post($this->getEndpoint(), $headers, $xml)
            ->send();

        print_r($httpResponse);
        die();

        return $this->response = new RedirectResponse($this, $httpResponse->getBody());
    }

    /**
     * Get endpoint
     *
     * Returns endpoint depending on test mode
     *
     * @access protected
     * @return string
     */
    protected function getEndpoint() {
        return ($this->getTestMode() ? self::EP_HOST_TEST : self::EP_HOST_LIVE) . self::EP_PATH;
    }
}
