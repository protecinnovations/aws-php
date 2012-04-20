<?php

namespace \Amazon\Interfaces\SES;

interface Request
{
    public function setAction($action);
    public function getResponse();
}
