<?php

namespace Amazon\Service;

interface SESInterface
{
    public function __construct(
        \Amazon\CredentialsInterface $credentials,
        \Amazon\SES\RequestInterface $request = null,
        \Amazon\RegionInterface $region = null
    );
    public function listVerifiedEmailAddresses();
    public function verifyEmailAddress($email);
    public function deleteVerifiedEmailAddress($email);
    public function getSendQuota();
    public function getSendStatistics();
    public function sendEmail(\Amazon\SES\MessageInterface $message);
}
