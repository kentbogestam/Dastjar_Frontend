#!/bin/sh

# Script name : cumbari_delete_coupon.sh
# Dumps all db in advertise 
dbname="cumbari_admin"
date=`date +%Y-%m-%d`



wget --no-check-certificate https://advertise.dastjar.com/commonAction.php?act=deleteCoupon -o /tmp/deleteCoupon.err
if [ $? -gt 0 ]; then
mailx -s "Erron in deleting coupon on advertise" admin@dastjar.com </dev/null
fi

rm /root/commonAction.php?act=deleteCoupon*
