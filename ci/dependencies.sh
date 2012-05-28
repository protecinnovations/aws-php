#!/bin/bash

pyrus channel-discover pear.survivethedeepend.com
pyrus channel-discover hamcrest.googlecode.com/svn/pear
pyrus install deepend/Mockery --alldeps

phpenv rehash
