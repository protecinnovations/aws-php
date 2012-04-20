<?php

namespace Amazon\Service;

use \Amazon\Interfaces;


class SES implements Interfaces\Authenticated
{
    protected $credentials;
    protected $region;
    protected $request = null;

    public function authenticate(Interfaces\Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    public function __construct(\Amazon\Interfaces\Region $region, \Amazon\Interfaces\Credentials $credentials, \Amazon\Interfaces\SES\Request $request)
    {
        $this->authenticate($credentials);
        $this->region = $region;
        $this->request = $request;
        $this->request->setCredentials($credentials);
        $this->request->setHost($region->getSESHost());
    }

    public function listVerifiedEmailAddresses()
    {
        $this->request->setMethod(\Amazon\Request::METHOD_GET);

        $this->request->setAction('ListVerifiedEmailAddresses');

        $response = $this->request->getResponse();

        $addresses = array();

        foreach($response['response']->ListVerifiedEmailAddressesResult->VerifiedEmailAddresses->member as $address)
        {
            $addresses[] = $address;
        }

        return $addresses;
    }

    public function verifyEmailAddress($email)
    {
        $this->request->setMethod(\Amazon\Request::METHOD_POST);
        $this->request->setAction('VerifyEmailAddress');
        $this->request->addParameter('EmailAddress', $email);

        $response = $this->request->getResponse();

        if ($response['info']['http_code'] != 200)
        {
            throw new RuntimeException('Unknown HTTP Response Code');
        }

        return $this;
    }

    public function deleteVerifiedEmailAddress($email)
    {
        $this->request->setMethod(\Amazon\Request::METHOD_DELETE);
        $this->request->setAction('DeleteVerifiedEmailAddress');
        $this->request->addParameter('EmailAddress', $email);

        $response = $this->request->getResponse();

        if ($response['info']['http_code'] != 200)
        {
            throw new RuntimeException('Unknown HTTP Response Code');
        }

        return $this;
    }

    public function getSendQuota()
    {
        $this->request->setMethod(\Amazon\Request::METHOD_GET);
        $this->request->setAction('GetSendQuota');

        $response = $this->request->getResponse();

        if ($response['info']['http_code'] != 200)
        {
            throw new RuntimeException('Unknown HTTP Response Code');
        }

        $quotas = array(
            'max_send_rate' => $response['response']->GetSendQuotaResult->MaxSendRate,
            'max_in_24_hours' => $response['response']->GetSendQuotaResult->Max24HourSend,
            'sent_in_last_24_hours' => $response['response']->GetSendQuotaResult->SentLast24Hours,
        );

        return $quotas;
    }

    public function getSendStatistics()
    {
        $this->request->setMethod(\Amazon\Request::METHOD_GET);
        $this->request->setAction('GetSendStatistics');

        $response = $this->request->getResponse();

        if ($response['info']['http_code'] != 200)
        {
            throw new RuntimeException('Unknown HTTP Response Code');
        }

        $data_points = array();

        foreach ($response['response']->GetSendStatisticsResult->SendDataPoints->member AS $data_point)
        {
            $data_points['bounces'][$data_point->Timestamp] = $data_point->Bounces;
			$data_points['complaints'][$data_point->Timestamp] = $data_point->Complaints;
			$data_points['delivery_attempts'][$data_point->Timestamp] = $data_point->DeliveryAttempts;
			$data_points['rejects'][$data_point->Timestamp] = $data_point->Rejects;
        }

        return $data_points;
    }

    public function sendEmail(\Amazon\SES\Message $message)
    {
        if (!$message->isValid())
        {
            throw new InvalidArgumentException('Message is not valid');
        }

    }
}

