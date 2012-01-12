#!/bin/bash
#
# Author: T. Zengerink

# Script settings
DIR=$(dirname $BASH_SOURCE)
USER="[username]"
PASS="[password]"
DBASE="[database_name]"

# Set variables
YEAR=`date +%Y`
MONTH=`date +%m`
DAY=`date +%d`
TIME=`date +%H%M`

# Create backup directory for today
mkdir -p "$DIR/backups/$YEAR/$MONTH/$DAY"

##
# Dump database using --single-transaction
#
# [!!] This only works with InnoDB, all other formats will be ignored
#
mysqldump -u $USER -p$PASS $DBASE --single-transaction > "$DIR/backups/$YEAR/$MONTH/$DAY/$TIME.sql"
