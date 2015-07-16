#!/bin/bash

sudo vzctl stop $1
sudo cp /etc/sysconfig/vz-scripts/$1.conf /etc/sysconfig/vz-scripts/$1.conf.REINSTALL
sudo vzctl destroy $1
sudo vzctl create $1 --ostemplate $2
sudo mv -f /etc/sysconfig/vz-scripts/$1.conf.REINSTALL /etc/sysconfig/vz-scripts/$1.conf
sudo vzctl start $1
sudo vzctl set $1 --userpasswd root:$3 --save