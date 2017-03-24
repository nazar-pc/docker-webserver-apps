FROM nazarpc/webserver:php-fpm-v1
MAINTAINER Nazar Mokrynskyi <nazar@mokrynskyi.com>

# < From nextcloud-php-fpm image
RUN \

# We'll need LibreOffice for Documents app

	apt-get update && \
	apt-get upgrade -y && \
	apt-get install --no-install-recommends -y libreoffice && \
	apt-get clean && \
	rm -rf /var/lib/apt/lists/*
# From nextcloud-php-fpm image >

ENV TERM=xterm

CMD \
	cd /data/nginx/www && \
	watch --no-title --interval 600 "test ! -f config/autoconfig.php && su git -c 'php occ background:cron' && su git -c 'php -f cron.php > /dev/null 2>&1'"
