#!/bin/bash

# http://serverfault.com/a/59279/118161
"$@" 2> >(tee color_stderr.log | while read line; do echo -e "\e[01;31m$line\e[0m" >&2; done)

