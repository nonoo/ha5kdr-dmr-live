#!/bin/sh

if [ -z "`ps ax | grep ha5kdr-dmr-live-update.sh | grep -v grep`" ]; then
	/home/nonoo/bin/ha5kdr-dmr-live-update.sh &
fi
