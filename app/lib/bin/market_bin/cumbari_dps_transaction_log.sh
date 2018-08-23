#!/bin/sh

# Script name : cumbari_dps_trasaction_dump.sh
# Inserts replicade tables in dpsdb 
dbname="dpsdb"
date=`date +%Y-%m-%d`

mysqldump -w "purchase_time between DATE(ADDDATE(NOW(), INTERVAL -1 DAY)) AND ADDDATE(NOW(), INTERVAL -0 DAY)" dpsdb transaction_receipt > /tmp/dpsdp_trans_dump.sql
if [ $? -gt 0 ]; then
mailx -s "Erron in dumping trans_log on cumbari.com" admin@cumbari.com < /dev/null
else
scp -P 56156 -i /root/kent_cum_key.pem /tmp/dpsdp_trans_dump.sql ec2-user@advertise.cumbari.com:/var/cumbari/load/dpsdp_trans_dump.sql
fi


#move backup to Amazon S3

#s3put cumbari-backup/transactions/$date'_dpsdp_trans_dump.sql' /tmp/dpsdp_trans_dump.sql
