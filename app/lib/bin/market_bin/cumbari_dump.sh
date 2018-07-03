#!/bin/sh

# Script name : dpsdb_dump.sh
# Dumps all db in www 
dbname="dpsdb"
date=`date +%d`

rm -f /var/cumbari/backup/old/*

mv /var/cumbari/backup/*.bk /var//cumbari/backup/old

if [ -f /var//cumbari/backup/dpsdb_dump.sql ]
then
mv /var/cumbari/backup/dpsdb_dump.sql /var//cumbari/backup/$date'_dpsdb_dump.sql.bk'
fi

mysqldump dpsdb -u root > /var/cumbari/backup/dpsdb_dump.sql
if [ $? -gt 0 ]; then
mailx -s "Erron in backup  of dpsdb" admin@cumbari.com </dev/null
fi


if [ -f /var/cumbari/backup/mysql_dump.sql ]
then
mv /var/cumbari/backup/mysql_dump.sql /var/cumbari/backup/$date'_mysql_dump.sql.bk'
fi

mysqldump -u root mysql > /var/cumbari/backup/mysql_dump.sql
if [ $? -gt 0 ]; then
mailx -s "Erron in dumping mysqldb on market.cumbari.com" admin@cumbari.com </dev/null
fi



#if [ -f /var/cumbari/backup/information_schema.sql ]
#then
#mv /var/cumbari/backup/information_schema.sql /var//cumbari/backup/$date'_information_schema.sql.bk'
#fi
#mysqldump -u root information_schema > /var/cumbari/backup/information_schema.sql

#if [ $? -gt 0 ]; then
#mailx -s "Erron in dumping information_schema on market.cumbari.com" admin@cumbari.com </dev/null
#fi

#move backup to Amazon S3
if [ -f /var/cumbari/backup/dpsdb_dump.sql ]
then
cp /var/cumbari/backup/dpsdb_dump.sql /tmp/$date'_dpsdb_dump.sql'
fi

aws s3 mv /tmp/$date'_dpsdb_dump.sql' s3://cumbari-backup/transactions/ --acl public-read
