#!/bin/bash
clear

if [ $(id -u) != "0" ]; then
    echo "You must be the root to run this uninstall script." >&2
    exit 1
fi

cat <<EOF
######################################
This uninstall script will remove BoxCtrl-VZ from this system. If you are ready to uninstall, type "yes". Otherwise, type "no".
######################################
EOF

read useranswer

if [ "$useranswer" == "yes" ];
then
	echo "Uninstall starting..."
else
	echo "Uninstall stopped."
	exit
fi

## remove folders and files

rm -fr /srv/www
yum -y remove lighttpd
yum -y remove lighttpd-fastcgi
yum -y remove php-cli

## remove boxctrl user
userdel -r boxctrl
sed -i '/boxctrl/d' /etc/sudoers

## uninstall complete
clear
echo "BoxCtrl has been uninstalled."
exit