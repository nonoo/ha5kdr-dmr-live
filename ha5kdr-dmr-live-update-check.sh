#!/bin/sh

if [ -z "`ps a | grep ha5kdr-dmr-live-update.sh`" ]; then
	/home/nonoo/bin/ha5kdr-dmr-live-update.sh &
fi
