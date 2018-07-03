#!/bin/sh

# Script name : cumbari_usage_log.sh
# Dumps all db in advertise 
dbname="cumbari_admin"
date=`date +%Y-%m-%d`

rm -f /var/cumbari/load/old/*

mv /var/cumbari/load/*.bk /var/cumbari/load/old

if [ -s /var/cumbari/load/dpsdp_usage_dump.sql ]
then

	mysql cumbari_admin  < /var/cumbari/load/dpsdp_usage_dump.sql
	if [ $? -gt 0 ]; then
		mailx -s "Erron in loading  of dpsd_usage on advertise" admin@dastjar.com </dev/null
	fi

fi


if [ -f /var/cumbari/load/dpsdp_usage_dump.sql ]
then
mv /var/cumbari/load/dpsdp_usage_dump.sql /var/cumbari/load/$date'_dpsdp_usage_dump.sql.bk'
fi


