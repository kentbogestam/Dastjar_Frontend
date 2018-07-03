#!/bin/sh 
# Script converting db replica dump from admin to dpserver
# This script need to be checked when db tables are changed


#Coupon tabellen:
#  store_id - heter "store" i DPServer

coupon=`grep -n 'CREATE TABLE\ \`coupon\`'  /tmp/replica/dump.sql | cut -d: -f1` 
coupon=`expr $coupon + 2`
sed ${coupon}'s/store_id/store/' /tmp/replica/dump.sql > /tmp/test.sql

#  category_id - heter "category" i DPServer
cat=`expr $coupon + 5`
sed ${cat}'s/category_id/category/' /tmp/test.sql > /tmp/test2.sql


 #category_names_lang_list tabellen:
 #   *   `category_id` - heter "category" i DPServer  
category_names_lang_list=`grep -n 'CREATE TABLE\ \`category_names_lang_list\`'  /tmp/replica/dump.sql | cut -d: -f1` 
category_names_lang_list=`expr $category_names_lang_list + 1`
sed ${category_names_lang_list}'s/category_id/category/' /tmp/test2.sql  > /tmp/test.sql

# PRIMARY KEY  
category_names_lang_list=`expr $category_names_lang_list + 3`
sed ${category_names_lang_list}'s/category_id/category/' /tmp/test.sql  > /tmp/test2.sql
 
 #coupon_limit_period_list tabellen:
 #   * `coupon_id` - heter "coupon" i DPServer 
coupon_limit_period_list=`grep -n 'CREATE TABLE\ \`coupon_limit_period_list\`' /tmp/replica/dump.sql | cut -d: -f1` 
coupon_limit_period_list=`expr $coupon_limit_period_list + 1`
sed ${coupon_limit_period_list}'s/coupon_id/coupon/' /tmp/test2.sql  > /tmp/test.sql

#  PRIMARY KEY  
coupon_limit_period_list=`expr $coupon_limit_period_list + 3`
sed ${coupon_limit_period_list}'s/coupon_id/coupon/' /tmp/test.sql  > /tmp/test2.sql

 
#coupon_slogan_lang_list tabellen - heter "coupon_offer_title_lang_list" i DPServer:
#   *  coupon_id - heter "coupon" i DPServer  
coupon_offer_title_lang_list=`grep -n 'CREATE TABLE\ \`coupon_offer_title_lang_list\`'  /tmp/replica/dump.sql | cut -d: -f1` 
coupon_offer_title_lang_list=`expr $coupon_offer_title_lang_list + 1`
sed ${coupon_offer_title_lang_list}'s/coupon_id/coupon/' /tmp/test2.sql  > /tmp/test.sql

#  PRIMARY KEY  
coupon_offer_title_lang_list=`expr $coupon_offer_title_lang_list + 3`
sed ${coupon_offer_title_lang_list}'s/coupon_id/coupon/' /tmp/test.sql  > /tmp/test2.sql

 
 #coupon_sub_slogan_lang_list -heter "coupon_offer_slogan_lang_list" i DPServer
 #   * coupon_id - heter "coupon" i DPServer   
coupon_offer_slogan_lang_list=`grep -n 'CREATE TABLE\ \`coupon_offer_slogan_lang_list\`'  /tmp/replica/dump.sql | cut -d: -f1` 
coupon_offer_slogan_lang_list=`expr $coupon_offer_slogan_lang_list + 1`
sed ${coupon_offer_slogan_lang_list}'s/coupon_id/coupon/' /tmp/test2.sql  > /tmp/test.sql
 
#  PRIMARY KEY 
coupon_offer_slogan_lang_list=`expr $coupon_offer_slogan_lang_list + 3`
sed ${coupon_offer_slogan_lang_list}'s/coupon_id/coupon/' /tmp/test.sql  > /tmp/test2.sql

# Fix foreign key
sed s/FOREIGN\ KEY\ \(\`category_id\`\)/FOREIGN\ KEY\ \(\`category\`\)/ /tmp/test2.sql > /tmp/test.sql
sed s/FOREIGN\ KEY\ \(\`store_id\`\)/FOREIGN\ KEY\ \(\`store\`\)/ /tmp/test.sql > /tmp/test2.sql
sed s/FOREIGN\ KEY\ \(\`coupon_id\`\)/FOREIGN\ KEY\ \(\`coupon\`\)/ /tmp/test2.sql > /tmp/test.sql

# Fix KEY 
sed s/KEY\ \`store_id\`\ \(\`store_id\`\)/KEY\ \`store\`\ \(\`store\`\)/ /tmp/test.sql > /tmp/test2.sql
                    

                   

