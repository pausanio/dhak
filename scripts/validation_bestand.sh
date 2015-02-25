#!/bin/bash
cd /Users/holgersimon/Sites/2_extern/dhak/1_Website/3_Entwicklung/4_Sourcen/dev.historischesarchivkoeln.de/
_now=$(date +"%m_%d_%Y")
_file="/Users/holgersimon/Sites/2_extern/dhak/1_Website/3_Entwicklung/4_Sourcen/dev.historischesarchivkoeln.de/scripts/log/validation.bestand_$_now.txt"
/usr/local/php5/bin/php symfony dhastk:queue-worker "validation.bestand" --env=prod >> "$_file"

