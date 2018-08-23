#!/bin/sh

# Script name : cumbari_dump.sh
# Replicating needed tables to Oves server

if [ -d /tmp/replica ]; then

mysqldump --ignore-table=cumbari_admin.brands --ignore-table=cumbari_admin.c_s_rel --ignore-table=cumbari_admin.campaign --ignore-table=cumbari_admin.campaign_limit_period_list --ignore-table=cumbari_admin.campaign_offer_slogan_lang_list --ignore-table=cumbari_admin.campaign_offer_sub_slogan_lang_list --ignore-table=cumbari_admin.ccode --ignore-table=cumbari_admin.company --ignore-table=cumbari_admin.cost --ignore-table=cumbari_admin.employer --ignore-table=cumbari_admin.product --ignore-table=cumbari_admin.product_offer_slogan_lang_list --ignore-table=cumbari_admin.product_price_list --ignore-table=cumbari_admin.reselleragrement --ignore-table=cumbari_admin.user --ignore-table=cumbari_admin.coupon_usage_statistics --ignore-table=cumbari_admin.transaction_receipt cumbari_admin --ignore-table=cumbari_admin.user_support --ignore-table=cumbari_admin.user_activity > /tmp/replica/rdump.sql

if [ $? -gt 0 ]; then
mailx -s "Erron in creating replica on advertise.cumbari.com" admin@dastjar.com </dev/null
else
#scp -i /root/cumbariadmin1_eu.pem /tmp/replica/rdump.sql root@market.cumbari.com:/tmp
scp -P 56156 -i /root/kent_cum_key.pem /tmp/replica/rdump.sql ec2-user@52.51.68.111:/tmp
fi

if [ $? -gt 0 ]; then
mailx -s "Erron in sending replica from advertise to market.dastjar.com" admin@cumbari.com </dev/null
fi

fi


