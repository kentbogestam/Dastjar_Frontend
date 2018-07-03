#!/bin/sh

# Script name : cumbari_dps_usage_log_dump.sh
# dumps  and cleans tables in dpsdb 
dbname="dpsdb"
date=`date +%Y-%m-%d`

mysqldump -u root dpsdb coupon_usage_statistics > /tmp/dpsdp_usage_dump.sql
if [ $? -gt 0 ]; then
mailx -s "Erron in dumping dpsdp_usage_dump.sql on cumbari.com" admin@cumbari.com < /dev/null
else
scp -P 56156 -i /root/kent_cum_key.pem /tmp/dpsdp_usage_dump.sql ec2-user@advertise.cumbari.com:/var/cumbari/load/dpsdp_usage_dump.sql
fi

mysql dpsdb < /usr/local/bin/coupon_usage_statistics.sql
 if [ $? -gt 0 ]; then
  mailx -s "Erron in cleaning usage on cumbari.com" admin@cumbari.com </dev/null
fi


#move backup to Amazon S3

s3put cumbari-backup/transactions/$date'_dpsdp_usage_dump.sql' /tmp/dpsdp_usage_dump.sql
