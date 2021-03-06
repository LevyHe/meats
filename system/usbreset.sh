#!/bin/bash

usbreset_bin=$HOME/bin/usbreset

if [ -z "$1" ] ; then
	echo "Usage: $0 device-filename"
	echo "Example $0 /dev/bus/usb/001/011"
	exit
fi

if [ ! -f $usbreset_bin ] ; then
	mkdir -p `dirname $usbreset_bin`
	gcc `dirname $0`/usbreset.c -o $usbreset_bin
fi

if [ -e $1 ]; then
	device=$1
else
	device=`lsusb | awk "/$@/"' { sub(":", ""); print "/dev/bus/usb/" $2 "/" $4 }'`
	echo Inferred device: $device
fi

sudo $usbreset_bin $device

