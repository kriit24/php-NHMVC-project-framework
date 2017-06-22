#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
SOURCE="${SOURCE/\/command/}"
PHP_FILE=$SOURCE/index.php

if [ $SOURCE == "command" ]
then
	unset SOURCE
	PHP_FILE=index.php
fi

if [ "$1" == "Cron" ]
then
	if [ -z "$2" ]
	then
		php $PHP_FILE Cron
	else
		php $PHP_FILE Cron/$2
	fi
elif [ -n "$1" ] && [ -z "$2" ]
then
	method=$1
	method=`echo ${method:0:1} | tr  '[a-z]' '[A-Z]'`${method:1}
	php $PHP_FILE Command/Command/$method
elif [ -n "$1" ] && [ -n "$2" ]
then
	method=$1
	param=$@
	param=`echo ${param//$method/}`
	param=`echo "$param" | tr -d ' '`
	method=`echo ${method:0:1} | tr  '[a-z]' '[A-Z]'`${method:1}
	php $PHP_FILE Command/Command/$method/?param=$param
else
	php $PHP_FILE Command/Command/Index
fi