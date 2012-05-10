<?php

namespace Amazon\SES;

class Message implements \Amazon\SES\MessageInterface
{
    protected $to = array();
    protected $cc = array();
    protected $bcc = array();
    protected $reply_to = array();
    protected $from = null;
    protected $return_path = null;
    protected $subject = null;
    protected $body_text = null;
    protected $body_html = null;
    protected $charset_subject = 'UTF-8';
    protected $charset_body_text = 'UTF-8';
    protected $charset_body_html = 'UTF-8';

    public function addTo($to)
    {
        if (is_array($to))
        {
            $this->to = array_merge($this->to, $to);
        }
        else
        {
            $this->to[] = $to;
        }

        return $this;
    }

    public function setTo($to)
    {
        if (is_array($to))
        {
            $this->to = $to;
        }
        else
        {
            $this->to = array($to);
        }

        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function addCc($cc)
    {
        if (is_array($cc))
        {
            $this->cc = array_merge($this->cc, $cc);
        }
        else
        {
            $this->cc[] = $cc;
        }

        return $this;
    }

    public function setCc($cc)
    {
        if (is_array($cc))
        {
            $this->cc = $cc;
        }
        else
        {
            $this->cc = array($cc);
        }

        return $this;
    }

    public function getCc()
    {
        return $this->cc;
    }

    public function addBcc($bcc)
    {
        if (is_array($bcc))
        {
            $this->bcc = array_merge($this->bcc, $bcc);
        }
        else
        {
            $this->bcc[] = $bcc;
        }

        return $this;
    }

    public function setBcc($bcc)
    {
        if (is_array($bcc))
        {
            $this->bcc = $bcc;
        }
        else
        {
            $this->bcc = array($bcc);
        }

        return $this;
    }

    public function getBcc()
    {
        return $this->bcc;
    }

    public function addReplyTo($reply_to)
    {
        if (is_array($reply_to))
        {
            $this->reply_to = array_merge($this->reply_to, $reply_to);
        }
        else
        {
            $this->reply_to[] = $reply_to;
        }

        return $this;
    }

    public function setReplyTo($reply_to)
    {
        if (is_array($reply_to))
        {
            $this->reply_to = $reply_to;
        }
        else
        {
            $this->reply_to = array($reply_to);
        }

        return $this;
    }

    public function getReplyTo()
    {
        return $this->reply_to;
    }

    public function setFrom($name, $email)
    {
        $this->from = sprintf('"%s" <%s>', $name, $email);

        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setReturnPath($return_path)
    {
        $this->return_path = $return_path;

        return $this;
    }

    public function getReturnPath()
    {
        return $this->return_path;
    }

    public function hasReturnPath()
    {
        return (!is_null($this->return_path) && !empty($this->return_path));
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function hasSubject()
    {
        return (!is_null($this->subject) && !empty($this->subject));
    }

    public function setBodyText($body_text)
    {
        $this->body_text = $body_text;
        return $this;
    }

    public function getBodyText()
    {
        return $this->body_text;
    }

    public function hasBodyText()
    {
        return (!is_null($this->body_text) && !empty($this->body_text));
    }

    public function setBodyHtml($body_html)
    {
        $this->body_html = $body_html;
        return $this;
    }

    public function getBodyHtml()
    {
        return $this->body_html;
    }

    public function hasBodyHtml()
    {
        return (!is_null($this->body_html) && !empty($this->body_html));
    }

    public function setCharsetSubject($charset_subject)
    {
        $this->charset_subject = $charset_subject;
        return $this;
    }

    public function getCharsetSubject()
    {
        return $this->charset_subject;
    }

    public function setCharsetBodyText($charset_body_text)
    {
        $this->charset_body_text = $charset_body_text;
        return $this;
    }

    public function getCharsetBodyText()
    {
        return $this->charset_body_text;
    }

    public function setCharsetBodyHtml($charset_body_html)
    {
        $this->charset_body_html = $charset_body_html;
        return $this;
    }

    public function getCharsetBodyHtml()
    {
        return $this->charset_body_html;
    }

    public function isValid()
    {
        if (empty($this->to))
        {
            return false;
        }

        if (is_null($this->from) || empty($this->from))
        {
            return false;
        }

        if (!$this->hasSubject() && !$this->hasBodyHtml() && !$this->hasBodyText())
        {
            return false;
        }

        return true;
    }
}
