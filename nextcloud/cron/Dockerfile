FROM nazarpc/webserver:php-fpm-v1
MAINTAINER Nazar Mokrynskyi <nazar@mokrynskyi.com>

# < From nextcloud-php-fpm image (+ procps)
RUN \
# We'll need LibreOffice for Documents app and Intl extension for Nextcloud itself
	apt-get update && \
	apt-get upgrade -y && \
	apt-get install --no-install-recommends -y libreoffice libicu-dev procps && \
	apt-get clean && \
	rm -rf /var/lib/apt/lists/* && \
# And intl for Nextcloud itself
	mv /usr/local/etc_dist /usr/local/etc && \
	docker-php-ext-install intl && \
	mv /usr/local/etc /usr/local/etc_dist
# From nextcloud-php-fpm image >


ENV TERM=xterm

ENTRYPOINT []

CMD \
	cd /data/nginx/www && \
	watch --no-title --interval 600 "test ! -f config/autoconfig.php && su git -c 'php occ background:cron' && su git -c 'php -f cron.php > /dev/null 2>&1'"
