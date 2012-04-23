<?php

namespace \Amazon\Interfaces\SES;

interface Message
{
    public function addTo($to);
    public function setTo($to);
    public function getTo();

    public function addCc($cc);
    public function setCc($cc);
    public function getCc();

    public function addBcc($bcc);
    public function setBcc($bcc);
    public function getBcc();

    public function addReplyTo($reply_to);
    public function setReplyTo($bcc);
    public function getReplyTo();

    public function setFrom($name, $email);
    public function getFrom();

    public function setReturnPath($return_path);
    public function getReturnPath();
    public function hasReturnPath();

    public function setSubject($subject);
    public function getSubject();
    public function hasSubject();

    public function setBodyText($body_text);
    public function getBodyText();
    public function hasBodyText();

    public function setBodyHtml($body_html);
    public function getBodyHtml();
    public function hasBodyHtml();

    public function setCharsetSubject($charset_subject);
    public function getCharsetSubject();

    public function setCharsetBodyText($charset_body_text);
    public function getCharsetBodyText();

    public function setCharsetBodyHtml($charset_body_html);
    public function getCharsetBodyHtml();

    public function isValid();
}
