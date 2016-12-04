#!/bin/bash

mplayer -ao alsa:device=hw=0.0  -really-quiet -noconsolecontrols -fs -slave -input file=/tmp/mplayer-fifo -playlist <(find "$1" -type f) >/dev/null 2>&1 &

echo "Playing!"

