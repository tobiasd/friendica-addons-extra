#!/bin/sh

# Name of program in ps-list 
NAME="Whatsapp WebClient"

# Dir to program
DIR="/root/whatsapp_webclient/"


clear

if ( ps -ef | grep coffee | grep -v grep)
then
	echo "$NAME is running..."
else
	echo "$NAME  is not running, starting..."
	coffee  $DIR\/index.coffee &
	echo "$NAME restarted"
fi

exit 0
