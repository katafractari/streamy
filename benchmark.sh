#!/bin/bash - 
#===============================================================================
#
#          FILE:  benchmark.sh
# 
#         USAGE:  ./benchmark.sh 
# 
#   DESCRIPTION:  
# 
#       OPTIONS:  ---
#  REQUIREMENTS:  ---
#          BUGS:  ---
#         NOTES:  ---
#        AUTHOR: YOUR NAME (), 
#       COMPANY: 
#       CREATED: 05/24/2011 10:51:33 PM CEST
#      REVISION:  ---
#===============================================================================

set -o nounset                              # Treat unset variables as an error

stopwatch php php/Utils.php flag=debug "http://blank-tv.de.streams.bassdrive.com:8000"
