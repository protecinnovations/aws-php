<?php
namespace Amazon\Service;

use Mockery as M;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-05-09 at 16:47:40.
 */
class SESTest extends \PHPUnit_Framework_TestCase
{
    const METHOD_UNKNOWN = 0;
    const METHOD_GET = 1;
    const METHOD_POST = 2;
    const METHOD_PUT = 3;
    const METHOD_DELETE = 4;
    const METHOD_HEAD = 5;
    const METHOD_OPTIONS = 6;
    const METHOD_TRACE = 7;
    const METHOD_CONNECT = 8;

    /**
     * @var SES
     */
    protected $object;

    /**
     * @var \Amazon\CredentialsInterface
     */
    protected $credentials_mock;
    protected $request_mock;
    protected $region_mock;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->credentials_mock = M::mock('\Amazon\CredentialsInterface');
        $this->request_mock = M::mock('\Amazon\SES\RequestInterface');
        $this->region_mock = M::mock('\Amazon\RegionInterface');

        $this->request_mock->shouldReceive('setCredentials')->with($this->credentials_mock);
        $this->request_mock->shouldReceive('setHost')->with('localhost');

        $this->region_mock->shouldReceive('getSESHost')->andReturn('localhost');

        $this->object = new SES($this->credentials_mock, $this->request_mock, $this->region_mock);

        $this->addToAssertionCount(3);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        M::close();
    }

    public function testAuthenticate()
    {
        $this->assertAttributeSame($this->credentials_mock, 'credentials', $this->object);
    }

    public function testListVerifiedEmailAddresses()
    {
        $this->request_mock
            ->shouldReceive('setMethod')
            ->atLeast()
            ->once()
            ->with(self::METHOD_GET)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('setAction')
            ->atLeast()
            ->once()
            ->with('ListVerifiedEmailAddresses')
            ->andReturn($this->request_mock);

        $response = array();
        $response['info']['http_code'] = 200;
        $response['response'] = new \stdClass();

        $address = sha1(microtime());

        $addresses = array(
                $address,
                sha1($address),
            );

        $response['response']
            ->ListVerifiedEmailAddressesResult = new \stdClass();

        $response['response']
            ->ListVerifiedEmailAddressesResult
            ->VerifiedEmailAddresses = new \stdClass();

        $response['response']
            ->ListVerifiedEmailAddressesResult
            ->VerifiedEmailAddresses
            ->member = $addresses;

        $this->request_mock
            ->shouldReceive('getResponse')
            ->atLeast()
            ->once()
            ->andReturn($response);

        $this->assertSame($addresses, $this->object->ListVerifiedEmailAddresses());

        $this->addToAssertionCount(3);
    }

    public function testVerifyEmailAddress()
    {
        $address = sha1(microtime());

        $this->request_mock
            ->shouldReceive('setMethod')
            ->atLeast()
            ->once()
            ->with(self::METHOD_POST)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('setAction')
            ->atLeast()
            ->once()
            ->with('VerifyEmailAddress')
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('EmailAddress', $address);

        $response = array();
        $response['info']['http_code'] = 200;

        $this->request_mock
            ->shouldReceive('getResponse')
            ->atLeast()
            ->once()
            ->andReturn($response);

        $this->object->verifyEmailAddress($address);

        $this->addToAssertionCount(4);
    }

    public function testVerifyEmailAddressInvalid()
    {
        $address = sha1(microtime());

        $this->request_mock
            ->shouldReceive('setMethod')
            ->atLeast()
            ->once()
            ->with(self::METHOD_POST)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('setAction')
            ->atLeast()
            ->once()
            ->with('VerifyEmailAddress')
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('EmailAddress', $address);

        $response = array();
        $response['info']['http_code'] = 404;

        $this->request_mock
            ->shouldReceive('getResponse')
            ->atLeast()
            ->once()
            ->andReturn($response);

        $this->setExpectedException('\RuntimeException');

        $this->object->verifyEmailAddress($address);

        $this->addToAssertionCount(4);
    }

    public function testDeleteVerifiedEmailAddress()
    {
        $address = sha1(microtime());

        $this->request_mock
            ->shouldReceive('setMethod')
            ->atLeast()
            ->once()
            ->with(self::METHOD_DELETE)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('setAction')
            ->atLeast()
            ->once()
            ->with('DeleteVerifiedEmailAddress')
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('EmailAddress', $address);

        $response = array();
        $response['info']['http_code'] = 200;

        $this->request_mock
            ->shouldReceive('getResponse')
            ->atLeast()
            ->once()
            ->andReturn($response);

        $this->object->deleteVerifiedEmailAddress($address);

        $this->addToAssertionCount(4);
    }

    public function testDeleteVerifiedEmailAddressFail()
    {
        $address = sha1(microtime());

        $this->request_mock
            ->shouldReceive('setMethod')
            ->atLeast()
            ->once()
            ->with(self::METHOD_DELETE)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('setAction')
            ->atLeast()
            ->once()
            ->with('DeleteVerifiedEmailAddress')
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('EmailAddress', $address);

        $response = array();
        $response['info']['http_code'] = 404;

        $this->request_mock
            ->shouldReceive('getResponse')
            ->atLeast()
            ->once()
            ->andReturn($response);

        $this->setExpectedException('\RuntimeException');
        $this->object->deleteVerifiedEmailAddress($address);

        $this->addToAssertionCount(4);
    }

    public function testGetSendQuota()
    {
        $this->request_mock
            ->shouldReceive('setMethod')
            ->atLeast()
            ->once()
            ->with(self::METHOD_GET)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('setAction')
            ->atLeast()
            ->once()
            ->with('GetSendQuota')
            ->andReturn($this->request_mock);

        $response = array();
        $response['info']['http_code'] = 200;
        $response['response'] = new \stdClass();

        $response['response'] = new \stdClass();

        $response['response']
            ->GetSendQuotaResult = new \stdClass();

        $response['response']
            ->GetSendQuotaResult
            ->MaxSendRate = 10;

        $response['response']
            ->GetSendQuotaResult
            ->Max24HourSend = 1000;

        $response['response']
            ->GetSendQuotaResult
            ->SentLast24Hours = 1000;

        $this->request_mock
            ->shouldReceive('getResponse')
            ->atLeast()
            ->once()
            ->andReturn($response);

        $this->assertSame(
            array(
                'max_send_rate' => 10,
                'max_in_24_hours' => 1000,
                'sent_in_last_24_hours' => 1000
            ),
            $this->object->getSendQuota()
        );

        $this->addToAssertionCount(3);
    }

    public function testGetSendQuotaFail()
    {
        $this->request_mock
            ->shouldReceive('setMethod')
            ->atLeast()
            ->once()
            ->with(self::METHOD_GET)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('setAction')
            ->atLeast()
            ->once()
            ->with('GetSendQuota')
            ->andReturn($this->request_mock);

        $response = array();
        $response['info']['http_code'] = 404;
        $response['response'] = new \stdClass();

        $this->request_mock
            ->shouldReceive('getResponse')
            ->atLeast()
            ->once()
            ->andReturn($response);

        $this->setExpectedException('\RuntimeException');

        $this->object->getSendQuota();

        $this->addToAssertionCount(3);
    }

    public function testGetSendStatistics()
    {
        $this->request_mock
            ->shouldReceive('setMethod')
            ->atLeast()
            ->once()
            ->with(self::METHOD_GET)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('setAction')
            ->atLeast()
            ->once()
            ->with('GetSendStatistics')
            ->andReturn($this->request_mock);

        $response = array();

        $response['info']['http_code'] = 200;

        $response['response'] = new \stdClass();

        $time1 = sha1(microtime());
        $time2 = sha1($time1);
        $time3 = sha1($time2);

        $response['response']
            ->getSendStatisticsResult = new \stdClass();

        $response['response']
            ->getSendStatisticsResult
            ->SendDataPoints = new \stdClass();

        $response['response']->GetSendStatisticsResult->SendDataPoints->member = array(
            (object) array(
                'Timestamp' => $time1,
                'Bounces' => 1,
                'Complaints' => 1,
                'DeliveryAttempts' => 1,
                'Rejects' => 1
            ),
            (object) array(
                'Timestamp' => $time2,
                'Bounces' => 2,
                'Complaints' => 2,
                'DeliveryAttempts' => 2,
                'Rejects' => 2
            ),
            (object) array(
                'Timestamp' => $time3,
                'Bounces' => 3,
                'Complaints' => 3,
                'DeliveryAttempts' => 3,
                'Rejects' => 3
            ),
        );

        $this->request_mock
            ->shouldReceive('getResponse')
            ->atLeast()
            ->once()
            ->andReturn($response);

        $result_array = array(
            'bounces' => array(
                $time1 => 1,
                $time2 => 2,
                $time3 => 3
            ),
            'complaints' => array(
                $time1 => 1,
                $time2 => 2,
                $time3 => 3
            ),
            'delivery_attempts' => array(
                $time1 => 1,
                $time2 => 2,
                $time3 => 3
            ),
            'rejects' => array(
                $time1 => 1,
                $time2 => 2,
                $time3 => 3
            )
        );

        $this->assertSame($result_array, $this->object->getSendStatistics());

        $this->addToAssertionCount(3);
    }

    public function testGetSendStatisticsFail()
    {
        $this->request_mock
            ->shouldReceive('setMethod')
            ->atLeast()
            ->once()
            ->with(self::METHOD_GET)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('setAction')
            ->atLeast()
            ->once()
            ->with('GetSendStatistics')
            ->andReturn($this->request_mock);

        $response = array();

        $response['info']['http_code'] = 404;

        $this->request_mock
            ->shouldReceive('getResponse')
            ->atLeast()
            ->once()
            ->andReturn($response);

        $this->setExpectedException('\RuntimeException');
        $this->object->getSendStatistics();

        $this->addToAssertionCount(3);
    }

    public function testSendEmail()
    {
        $mock_message = M::mock('\Amazon\SES\MessageInterface');

        $mock_message->shouldReceive('isValid')->andReturn(true);

        $to = sha1(microtime());
        $cc = sha1($to);
        $bcc = sha1($cc);
        $reply_to = sha1($bcc);
        $from = sha1($reply_to);
        $return_path = sha1($from);
        $subject = sha1($return_path);
        $body_text = sha1($subject);
        $body_html = sha1($body_text);

        $mock_message->shouldReceive('getTo')->andReturn(array($to));
        $mock_message->shouldReceive('getCc')->andReturn(array($cc));
        $mock_message->shouldReceive('getBcc')->andReturn(array($bcc));
        $mock_message->shouldReceive('getReplyTo')->andReturn(array($reply_to));
        $mock_message->shouldReceive('getFrom')->andReturn($from);
        $mock_message->shouldReceive('getFrom')->andReturn($from);
        $mock_message->shouldReceive('hasReturnPath')->andReturn(true);
        $mock_message->shouldReceive('getReturnPath')->andReturn($return_path);
        $mock_message->shouldReceive('hasSubject')->andReturn(true);
        $mock_message->shouldReceive('getSubject')->andReturn($subject);
        $mock_message->shouldReceive('getCharsetSubject')->andReturn('UTF-8');
        $mock_message->shouldReceive('hasBodyText')->andReturn(true);
        $mock_message->shouldReceive('hasBodyHtml')->andReturn(true);
        $mock_message->shouldReceive('getBodyText')->andReturn($body_text);
        $mock_message->shouldReceive('getBodyHtml')->andReturn($body_html);
        $mock_message->shouldReceive('getCharsetBodyText')->andReturn('UTF-8');
        $mock_message->shouldReceive('getCharsetBodyHtml')->andReturn('UTF-8');

        $this->request_mock
            ->shouldReceive('setMethod')
            ->atLeast()
            ->once()
            ->with(self::METHOD_POST)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('setAction')
            ->atLeast()
            ->once()
            ->with('SendEmail')
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Destination.ToAddresses.member.1', $to)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Destination.CcAddresses.member.1', $cc)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Destination.BccAddresses.member.1', $bcc)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('ReplyToAddresses.member.1', $reply_to)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Source', $from)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('ReturnPath', $return_path)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Message.Subject.Data', $subject)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Message.Subject.Charset', 'UTF-8')
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Message.Body.Text.Data', $body_text)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Message.Body.Text.Charset', 'UTF-8')
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Message.Body.Html.Data', $body_html)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Message.Body.Html.Charset', 'UTF-8')
            ->andReturn($this->request_mock);

        $response = array();
        $response['info']['http_code'] = 200;

        $this->request_mock
            ->shouldReceive('getResponse')
            ->atLeast()
            ->once()
            ->andReturn($response);

        $this->object->sendEmail($mock_message);

        $this->addToAssertionCount(33);
    }

    public function testSendEmailInvalidMessage()
    {
        $mock_message = M::mock('\Amazon\SES\MessageInterface');

        $mock_message->shouldReceive('isValid')->andReturn(false);

        $this->setExpectedException('\InvalidArgumentException');

        $this->object->sendEmail($mock_message);#

        $this->addToAssertionCount(1);
    }

    public function testSendEmailFail()
    {
        $mock_message = M::mock('\Amazon\SES\MessageInterface');

        $mock_message->shouldReceive('isValid')->andReturn(true);

        $to = sha1(microtime());
        $cc = sha1($to);
        $bcc = sha1($cc);
        $reply_to = sha1($bcc);
        $from = sha1($reply_to);

        $mock_message->shouldReceive('getTo')->andReturn(array($to));
        $mock_message->shouldReceive('getCc')->andReturn(array($cc));
        $mock_message->shouldReceive('getBcc')->andReturn(array($bcc));
        $mock_message->shouldReceive('getReplyTo')->andReturn(array($reply_to));
        $mock_message->shouldReceive('getFrom')->andReturn($from);
        $mock_message->shouldReceive('getFrom')->andReturn($from);
        $mock_message->shouldReceive('hasReturnPath')->andReturn(false);
        $mock_message->shouldReceive('hasSubject')->andReturn(false);
        $mock_message->shouldReceive('hasBodyText')->andReturn(false);
        $mock_message->shouldReceive('hasBodyHtml')->andReturn(false);

        $this->request_mock
            ->shouldReceive('setMethod')
            ->atLeast()
            ->once()
            ->with(self::METHOD_POST)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('setAction')
            ->atLeast()
            ->once()
            ->with('SendEmail')
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Destination.ToAddresses.member.1', $to)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Destination.CcAddresses.member.1', $cc)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('Destination.BccAddresses.member.1', $bcc)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with('ReplyToAddresses.member.1', $reply_to)
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
                        ->atLeast()
            ->once()
            ->with('Source', $from)
            ->andReturn($this->request_mock);

        $response = array();
        $response['info']['http_code'] = 404;

        $this->request_mock
            ->shouldReceive('getResponse')
                        ->atLeast()
            ->once()
            ->andReturn($response);

        $this->setExpectedException('\RuntimeException');

        $this->object->sendEmail($mock_message);

        $this->addToAssertionCount(19);
    }

    public function testSetMultiParam()
    {
        $prefix = sha1(microtime());

        $args = array(
            sha1($prefix),
            sha1(sha1($prefix))
        );

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with($prefix . '.1', sha1($prefix))
            ->andReturn($this->request_mock);

        $this->request_mock
            ->shouldReceive('addParameter')
            ->atLeast()
            ->once()
            ->with($prefix . '.2', sha1(sha1($prefix)))
            ->andReturn($this->request_mock);

        $this->addToAssertionCount(2);

        $this->object->setMultiParam($prefix, $args);
    }

    public function testConstructorWithDI()
    {
        $obj = new SES($this->credentials_mock, $this->request_mock, $this->region_mock);

        $this->assertAttributeSame($this->credentials_mock, 'credentials', $obj);
        $this->assertAttributeSame($this->request_mock, 'request', $obj);
        $this->assertAttributeSame($this->region_mock, 'region', $obj);
    }

    public function testConstructorWithoutDI()
    {
        $obj = new SES($this->credentials_mock);

        $this->assertAttributeSame($this->credentials_mock, 'credentials', $obj);
        $this->assertAttributeInstanceOf('\Amazon\SES\RequestInterface', 'request', $obj);
        $this->assertAttributeInstanceOf('\Amazon\RegionInterface', 'region', $obj);
    }
}
