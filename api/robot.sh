#!/bin/bash
max=30
for (( i=0; i <= $max; ++i ))
do
    /usr/bin/python3 /var/www/html/api/robot.py
    sleep 2
done