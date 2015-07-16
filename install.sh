#!/bin/bash
clear

## Check for root
if [ $(id -u) != "0" ]; then
    echo "You must be the root to run this install script." >&2
    exit 1
fi

## Check for 64-bit OS
if [ `uname -i` != x86_64 ]; then
    echo "BoxCtrl-VZ must be installed on CentOS 64-bit." >&2
    exit 1
fi

## Check for CentOS
os=`cat /etc/redhat-release | grep -i cent`
if [ "$?" -ne "0" ]; then
	echo "BoxCtrl-VZ can only be installed on CentOS."
	exit 1;
fi

# Check for YUM
if ! [ -f /usr/bin/yum ] ; then
	echo "YUM must be installed to continue."
	exit 1;
fi

## Welcome message
cat <<EOF
######################################
Only install BoxCtrl-VZ on a clean OS. If this is not a clean install of CentOS 64-bit (version 5), do not continue. You must
also install the OpenVZ kernel yourself, as this script will not do it for you. Now that you've read the warning, are you ready
to install BoxCtrl-VZ? Type "yes" or "no".
######################################
EOF

read useranswer

if [ "$useranswer" == "yes" ];
then
	echo "Installation starting..."
	sleep 2
else
	echo "Installation stopped."
	exit
fi

## Check for existing installation
if [ -f /srv/www/scripts/create_vps.sh ]
then
	echo "You already have BoxCtrl-VZ installed. Exiting now."
	exit
fi  

## Check for lighttpd installation
if [ -f /etc/lighttpd/lighttpd.conf ] 
then
	echo "It looks like you already have lighttpd installed. BoxCtrl-VZ can't be installed on your system if you already use Lighttpd. Stopping installation."
	exit
else
	wget http://packages.sw.be/rpmforge-release/rpmforge-release-0.3.6-1.el5.rf.x86_64.rpm
	rpm -Uvh rpmforge-release-0.3.6-1.el5.rf.x86_64.rpm
	yum -y install lighttpd
	chkconfig --levels 235 lighttpd on
	rm -f rpmforge-release-0.3.6-1.el5.rf.x86_64.rpm
	mv -f files/fastcgi.conf /etc/lighttpd/conf.d
	mv -f files/lighttpd.conf /etc/lighttpd/
	mv -f files/modules.conf /etc/lighttpd/
fi

## Install php-cgi for lighttpd
yum -y install lighttpd-fastcgi php-cli

## Move php.ini - backup old
mv /etc/php.ini /etc/php.ini.BACKUP
mv files/php.ini /etc/

## Setup the panel folders
mkdir /srv/www/blocked
mkdir /srv/www/ipaddresses
mkdir /srv/www/panel
mkdir /srv/www/scripts
mkdir /srv/www/superusers
mkdir /srv/www/users
rm -fr /srv/www/lighttpd

## Move the panel files
mv blocked /srv/www/
mv ipaddresses /srv/www/
mv panel /srv/www/
mv scripts /srv/www/
mv superusers /srv/www/
mv users /srv/www/
rm -fr files

## Create BoxCtrl user
useradd -M -s /sbin/nologin boxctrl

## Chown and chmod settings
chown -R boxctrl:boxctrl /srv/www
chown -R boxctrl:boxctrl /var/log/lighttpd
chmod +x /srv/www/scripts/create_vps.sh
chmod +x /srv/www/scripts/node_status.sh
chmod +x /srv/www/scripts/reinstall_os.sh

## Edit /etc/sudoers
sed -i 's/^Defaults    requiretty/# Defaults    requiretty/g' /etc/sudoers
echo "boxctrl ALL=(ALL) NOPASSWD: /sbin/service, /usr/sbin/vzctl, /usr/sbin/vzlist, /bin/mv, /bin/cp" >> /etc/sudoers

## Leave this to help BoxCtrl record installation stats
wget http://boxctrl.com/stats/installstats.php
rm -fr installstats.php

## Start the server
/etc/init.d/lighttpd start
clear

## Installation complete
cat <<EOF
######################################
Default Username: admin
Default Password: boxctrlpass

EOF

name=`hostname -i`
echo 'BoxCtrl-VZ admin login URL: http://YOUR-IP/admin:1111'
echo '######################################'

exit