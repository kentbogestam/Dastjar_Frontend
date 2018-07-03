#!/bin/sh

# Script name : cumbari_create_coupon.sh
# Dumps all db in advertise 
dbname="cumbari_admin"
date=`date +%Y-%m-%d`

# wget  --no-check-certificate https://advertise.dastjar.com/commonAction.php?act=createCoupon -o /tmp/createCoupon.err
wget  --no-check-certificate https://advertise.dastjar.com/callCreatCoupon.php -o /tmp/createCoupon.err

if [ $? -gt 0 ]; then
mailx -s "Erron in creating coupon on advertise" admin@dastjar.com </dev/null
fi

# rm /root/callCreatCoupon.ph*
