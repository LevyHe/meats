#!/bin/bash
device=`ls /dev/video? | head -n 1`
mplayer -fps 30 -tv driver=v4l2:width=1280:height=1024:device=$device tv://
