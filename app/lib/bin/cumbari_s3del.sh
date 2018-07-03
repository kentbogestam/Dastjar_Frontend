#!/bin/bash
 
# Usage cumbari_s3del.sh "name of file to delete in s3
# Usage cumbari_s3del.sh myfile
# Example: /usr/local/bin/cumbari_s3del.sh https://s3-eu-west-1.amazonaws.com/cumbari-coupons/upload/coupon/cpn_1328866683.jpg

INFILE=$1
OUTFILE=`echo $INFILE | cut -d/ -f4-7`
echo $OUTFILE

s3delete $OUTFILE 2>/dev/null
if [ $? -gt 0 ]; then
	mailx -s "Erron in delting file $INFILE  from s3" admin@dastjar.com </dev/null
fi
