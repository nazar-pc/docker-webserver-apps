#!/bin/bash
ocpath='/data/nginx/www'
htuser='git'
htgroup='git'

chown -R ${htuser}:${htgroup} ${ocpath}/
