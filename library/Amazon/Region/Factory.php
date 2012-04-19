<?php

namespace \Amazon\Region;

class Factory
{
    public function getRegion($region_identifier)
    {
        switch ($region_identifier)
        {
            case "eu-west-1":
                return new EU\West1;
            break;
        
            case "us-east-1":
                return new US\East1;
            break;
        }
    
    }
}