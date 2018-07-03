#!/bin/sh

# Script name : cumbari_dump.sh
# Dumps all db in advertise
dbname="cumbari_admin"
date=`date "+%d"`

rm -f /var/cumbari/backup/old/*

mv /var/cumbari/backup/*.bk /var/cumbari/backup/old/

if [ -f /var/cumbari/backup/cumbari_admin_dump.sql ]
then
mv /var/cumbari/backup/cumbari_admin_dump.sql /var/cumbari/backup/$date'_cumbari_admin_dump.sql.bk'
fi

mysqldump cumbari_admin > /var/cumbari/backup/cumbari_admin_dump.sql
if [ $? -gt 0 ]; then
mailx -s "Erron in backup  of cumbari_admin" admin@dastjar.com </dev/null
fi


if [ -f /var/cumbari/backup/mysql_dump.sql ]
then
mv /var/cumbari/backup/mysql_dump.sql /var/cumbari/backup/$date'_mysql_dump.sql.bk'
fi

mysqldump mysql > /var/cumbari/backup/mysql_dump.sql
if [ $? -gt 0 ]; then
mailx -s "Erron in dumping mysqldb on advertise.cumbari.com" admin@dastjar.com </dev/null
fi



#if [ -f /var/cumbari/backup/information_schema.sql ]
#then
#mv /var/cumbari/backup/information_schema.sql /var/cumbari/backup/$date'_information_schema.sql.bk'
#fi
#mysqldump information_schema > /var/cumbari/backup/information_schema.sql

#if [ $? -gt 0 ]; then
#mailx -s "Erron in dumping information_schema on advertise.cumbari.com" admin@cumbari.com </dev/null
#fi



#move backup to Amazon S3
if [ -f /var/cumbari/backup/cumbari_admin_dump.sql ]
then
cp /var/cumbari/backup/cumbari_admin_dump.sql /tmp/$date'_cumbari_admin_dump.sql'
fi

aws s3 mv /tmp/$date'_cumbari_admin_dump.sql'  s3://cumbari-backup/cumbari_admin/ --acl public-read
