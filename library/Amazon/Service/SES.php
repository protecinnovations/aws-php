<?php

namespace Amazon\Service;

use \Amazon\CredentialsInterface;
use \Amazon\SES\RequestInterface;
use \Amazon\RegionInterface;
use \Amazon\SES\Request as SESRequest;
use \Amazon\Region\Factory as RegionFactory;
use \Amazon\Request;
use \Amazon\SES\MessageInterface;
use \Amazon\AuthenticatedInterface;
use \Amazon\Service\SESInterface;

use \RuntimeException;
use \InvalidArgumentException;


class SES implements AuthenticatedInterface, SESInterface
{
    protected $credentials;
    protected $region;
    protected $request = null;

    public function setCredentials(CredentialsInterface $credentials)
    {
        $this->credentials = $credentials;
    }

    protected function getCredentials()
    {
        if (is_null($this->credentials))
        {
            throw new RuntimeException('No credentials set');
        }

        return $this->credentials;
    }

    public function authenticate(CredentialsInterface $credentials)
    {
        $this->credentials = $credentials;
    }

    public function __construct(CredentialsInterface $credentials = null, RequestInterface $request = null, RegionInterface $region = null)
    {
        if (!is_null($credentials)) {
            $this->setCredentials($credentials);
        }

        if (is_null($request)) {
            $request = new SESRequest();
        }

        if (is_null($region)) {
            $factory = new RegionFactory();
            $region = $factory->getRegion('us-east-1');
        }

        $this->region = $region;
        $this->request = $request;
        $this->request->setHost($region->getSESHost());
    }

    public function listVerifiedEmailAddresses()
    {
        $this->request->setCredentials($this->getCredentials());
        $this->request->setMethod(Request::METHOD_GET);

        $this->request->setAction('ListVerifiedEmailAddresses');

        $response = $this->request->getResponse();

        $addresses = array();

        foreach ($response['response']->ListVerifiedEmailAddressesResult->VerifiedEmailAddresses->member as $address)
        {
            $addresses[] = $address;
        }

        return $addresses;
    }

    public function verifyEmailAddress($email)
    {
        $this->request->setCredentials($this->getCredentials());
        $this->request->setMethod(Request::METHOD_POST);
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
        $this->request->setCredentials($this->getCredentials());
        $this->request->setMethod(Request::METHOD_DELETE);
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
        $this->request->setCredentials($this->getCredentials());
        $this->request->setMethod(Request::METHOD_GET);
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
        $this->request->setCredentials($this->getCredentials());
        $this->request->setMethod(Request::METHOD_GET);
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

    public function sendEmail(MessageInterface $message)
    {
        if (!$message->isValid())
        {
            throw new InvalidArgumentException('Message is not valid');
        }

        $this->request->setCredentials($this->getCredentials());
        $this->request->setMethod(Request::METHOD_POST);
        $this->request->setAction('SendEmail');

        $this->setTo($message->getTo());
        $this->setCc($message->getCc());
        $this->setBcc($message->getBcc());
        $this->setReplyTo($message->getReplyTo());

        $this->request->addParameter('Source', $message->getFrom());

        if ($message->hasReturnPath())
        {
            $this->request->addParameter('ReturnPath', $message->getReturnPath());
        }

        if ($message->hasSubject())
        {
            $this->request->addParameter('Message.Subject.Data', urlencode($message->getSubject()));
            $this->request->addParameter('Message.Subject.Charset', $message->getCharsetSubject());
        }

        if ($message->hasBodyText())
        {
            $this->request->addParameter('Message.Body.Text.Data', urlencode($message->getBodyText()));
            $this->request->addParameter('Message.Body.Text.Charset', $message->getCharsetBodyText());
        }

        if ($message->hasBodyHtml())
        {
            $this->request->addParameter('Message.Body.Html.Data', urlencode($message->getBodyHtml()));
            $this->request->addParameter('Message.Body.Html.Charset', $message->getCharsetBodyHtml());
        }

        $response = $this->request->getResponse();

        if ($response['info']['http_code'] != 200)
        {
            throw new RuntimeException('Unknown HTTP Response Code: ' . print_r ($response, true));
        }

        return $this;
    }

    protected function setTo($to)
    {
        $this->setMultiParam('Destination.ToAddresses.member', $to);
    }

    protected function setCc($cc)
    {
        $this->setMultiParam('Destination.CcAddresses.member', $cc);
    }

    protected function setBcc($bcc)
    {
        $this->setMultiParam('Destination.BccAddresses.member', $bcc);
    }

    protected function setReplyTo($reply_to)
    {
        $this->setMultiParam('ReplyToAddresses.member', $reply_to);
    }

    public function setMultiParam($param_prefix, $args)
    {
        $i = 1;
        foreach ($args AS $arg)
        {
            $this->request->addParameter(sprintf('%s.%d', $param_prefix, $i), $arg);
            $i++;
        }
    }
}
