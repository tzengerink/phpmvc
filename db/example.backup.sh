#!/bin/bash
#
# Author: T. Zengerink

# Script settings
DIR=$(dirname $BASH_SOURCE)
USER="[username]"
PASS="[password]"
DBASE="[database_name]"

##
# Dump database using --single-transaction
#
# [!!] This only works with InnoDB, all other formats will be ignored
#
mysqldump -u$USER -p$PASS $DBASE --single-transaction > "$DIR/backups/$DBASE-$(date +%Y%m%d-%H%M%S).sql"

# Delete backups of +5 days old
find "$DIR/backups" -type f -name "$DBASE-*.sql" -mmin +5 -delete
