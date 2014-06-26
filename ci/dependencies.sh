#!/bin/bash

rm -rf vendor composer.lock

curl -s http://getcomposer.org/installer | php

COMPOSER_PROCESS_TIMEOUT=4000 php -dapc.enable_cli=0 composer.phar --prefer-dist --dev -v install


