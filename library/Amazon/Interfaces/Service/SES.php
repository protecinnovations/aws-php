<?php

namespace \Amazon\Interfaces\Service;

interface SES
{
    public function __construct(\Amazon\Interfaces\Credentials $credentials, \Amazon\Interfaces\SES\Request $request = null, \Amazon\Interfaces\Region $region = null);
    public function listVerifiedEmailAddresses();
    public function verifyEmailAddress($email);
    public function deleteVerifiedEmailAddress($email);
    public function getSendQuota();
    public function getSendStatistics();
    public function sendEmail(\Amazon\SES\Message $message);
}
