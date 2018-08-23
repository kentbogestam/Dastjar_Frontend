#!/bin/sh

# Script name : cumbari_create_coupon.sh
# Dumps all db in advertise 
dbname="cumbari_admin"
date=`date +%Y-%m-%d`

wget --no-check-certificate https://advertise.dastjar.com/financialServices.php -o /tmp/finacialServices.err
if [ $? -gt 0 ]; then
mailx -s "Erron in financialService on advertise" admin@dastjar.com </dev/null
fi

rm /root/financialServices.php
