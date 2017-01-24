<?php

namespace Omnipay\MonerisCA\Message;

use DOMDocument;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Moneris CA XML Response
 */
class PurchaseResponse extends AbstractResponse {
    /**
     * Constructor
     *
     * @param RequestInterface $request Request
     * @param string           $data    Data
     *
     * @access public
     */
    public function __construct(RequestInterface $request, $data) {
        $this->request = $request;

        if (empty($data)) {
            throw new InvalidResponseException();
        }

        $responseDom = new DOMDocument;
        $responseDom->loadXML($data);

        $this->data = simplexml_import_dom($responseDom->documentElement->firstChild);
    }

    /**
     * Get message
     *
     * @access public
     * @return string
     */
    public function getMessage() {
        $message = 'UNKNOWN ERROR';

        if (isset($this->data->Message)) {
          $message = $this->data->Message->__toString();
        }

        return $message;
    }

    /**
     * Get transaction reference
     *
     * @access public
     * @return string
     */
    public function getTransactionReference() {
      if (isset($this->data->ReferenceNum)) {
        return $this->data->ReferenceNum;
      }
    }

    /**
     * Get is redirect
     *
     * @access public
     * @return boolean
     */
    public function isRedirect() {
        return false;
    }

    /**
     * Get is successful
     *
     * @access public
     * @return boolean
     */
    public function isSuccessful() {
        if (isset($this->data->ResponseCode)) {
          $response_code = $this->data->ResponseCode->__toString();
          $complete = ($this->data->Complete->__toString() === "true");
          if ($complete) {
            if ($response_code >= 0 && $response_code < 50) {
                return true;
            }
          }
        }
        return false;
    }
}
