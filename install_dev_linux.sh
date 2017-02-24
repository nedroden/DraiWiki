#!/bin/bash
git clone http://www.github.com/Chistaen/DraiWiki.git
sudo chmod -R 755 DraiWiki
cd DraiWiki
composer install
sudo nano public/Config.php

#
# NOTE:
# The database installer isn't working yet. Until it's
# working, you'll need to import the tables manually.
#
#
#echo "Please enter the username of your MySQL account:"
#read MYUSERNAME
#
#echo "Please enter the host name of your MySQL database:"
#read MYSERVERNAME
#
#while ! mysql -u "$MYUSERNAME" -h "$MYSERVERNAME" -p; do
#
#	echo "Can't connect. Please retry. Please enter the username of your MySQL account:"
#	read MYUSERNAME

#	echo "Please enter the host name of your MySQL database"
#	read MYSERVERNAME
#
#	mysql -u "$MYUSERNAME" -h "$MYSERVERNAME" -p
#
#done
#
#echo "A database connection has been established."
#
#while ! source install.sql; do
#
#	echo "Please enter the database name you wish to install DraiWiki in:"
#	read MYDBNAME
#
#	use "$MYDBNAME"
#	source install.sql
#
#done

nano install.sql

echo "Welcome to DraiWiki! Thank you for your interest in our Wiki software. You have just installed the latest version from
Github. If you have any questions, feel free to ask them at our support forum.

- The DraiWiki development team"