#!/bin/sh

# Script name : cumbari_table_replicate.sh
# Inserts replicade tables in dpsdb 
dbname="dpsdb"
if [ -f /tmp/rdump.sql ]; then

systemctl stop tomcat

 sleep 10

#  if [ -f /var/run/tomcat6.pid ]; then
#	  mysql dpsdb < /usr/local/bin/cumbari_drop.sql
#  fi


 mysql dpsdb < /tmp/rdump.sql
 if [ $? -gt 0 ]; then
  mailx -s "Erron in replication on cumbari.com" admin@cumbari.com </dev/null
 fi

 sleep 10
systemctl start tomcat
 if [ $? -gt 0 ]; then
	 mailx -s "Erron in starting Tomcat on cumbari.com" admin@cumbari.com </dev/null
 fi

fi
