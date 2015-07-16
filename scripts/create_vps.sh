#!/bin/bash

sudo vzctl create $1 --hostname $2 --ostemplate $9
sudo vzctl set $1 --nameserver $7 --nameserver $8 --userpasswd root:$3 --diskspace $6G:$6G --onboot yes --save
sudo vzctl set $1 --vmguarpages $4 --save
sudo vzctl set $1 --privvmpages $5 --save
sudo vzctl start $1