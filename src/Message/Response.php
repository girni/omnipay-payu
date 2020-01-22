<?php

namespace Omnipay\PayU\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;


class Response extends AbstractResponse implements RedirectResponseInterface
{
    protected $requestId = null;

    protected $headers = [];

    public function __construct(RequestInterface $request, $data, $headers = []) {

        $this->request = $request;
        $this->data = $data;
        $this->headers = $headers;
    }

    public function isSuccessful() {
        if (!empty($this->data['status']['statusCode']) && $this->data['status']['statusCode'] == 'SUCCESS') {
            return true;
        }

        return false;
    }

    public function getResponse() {
        if ($this->isSuccessful()) {
            return $this->data;
        }

        return null;
    }

    public function getMessage() {

        if (!$this->isSuccessful()) {

            if (isset($this->data['error_description'])) {
                return $this->data['error_description'];
            }
            else if (isset($this->data['status']['statusDesc'])) {
                return $this->data['status']['statusDesc'];
            }
        }

        return null;
    }

    public function getCode() {

        if (!$this->isSuccessful() && isset($this->data['status']['code'])) {
            return $this->data['status']['code'];
        }
        else if (isset($this->data['error'])) {
            return $this->data['error'];
        }

        return null;
    }

    public function isRedirect() {

        if (!empty($this->data['redirectUri'])) {
            return true;
        }

        return false;
    }

    public function redirect() {

        if (isset($this->data['redirectUri'])) {
            header('Location: '.$this->data['redirectUri']);
        }

        return false;
    }

    public function getRedirectUrl() {

        if (isset($this->data['redirectUri']) && !empty($this->data['redirectUri'])) {
            return $this->data['redirectUri'];
        }

        return null;
    }

    public function getTransactionReference() {

        if (isset($this->data['orderId'])) {
            return $this->data['orderId'];
        }

        return null;
    }


    public function getRedirectMethod() {

        return 'GET';
    }


    public function getRedirectData() {

        return null;
    }

}