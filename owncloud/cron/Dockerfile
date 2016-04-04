FROM nazarpc/webserver-apps:owncloud-php-fpm
MAINTAINER Nazar Mokrynskyi <nazar@mokrynskyi.com>

ENV TERM=xterm

CMD \
	cd /data/nginx/www/owncloud && \
	watch --no-title --interval 600 "test ! -f config/autoconfig.php && su git -c 'php occ background:cron' && su git -c 'php -f cron.php > /dev/null 2>&1'"
