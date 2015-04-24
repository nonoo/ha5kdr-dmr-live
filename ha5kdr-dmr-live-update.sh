#!/bin/sh

self=`readlink "$0"`
if [ -z "$self" ]; then
	self=$0
fi
scriptname=`basename "$self"`
scriptdir=${self%$scriptname}

cd $scriptdir

while [ 1 ]; do
	./ha5kdr-dmr-live-process.php
	sleep 5
done
