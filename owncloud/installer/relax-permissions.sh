#!/bin/bash
ocpath='/data/nginx/www/owncloud'
htuser='git'
htgroup='git'

chown -R ${htuser}:${htgroup} ${ocpath}/
