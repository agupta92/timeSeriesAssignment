#!/bin/bash

# check for process id
pid=`ps ax | grep -i userAlertConsumer.php | grep -iv grep | awk '{print $1}' | wc -l`

# check if pid is not an integer
while [ "$pid" -le 1 ]
do
  # start service
   cd /var/www/html/oorjan/timeSeriesAssignment
   nohup  php userAlertConsumer.php  > /home/ec2-user/test.log &
   pid=`ps ax | grep -i userAlertConsumer.php | grep -iv grep | awk '{print $1}' | wc -l`
done
exit 1;
