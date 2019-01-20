FROM nazarpc/webserver:php-fpm-v1
MAINTAINER Nazar Mokrynskyi <nazar@mokrynskyi.com>

RUN \
# We'll need LibreOffice for Documents app and Intl extension for Nextcloud itself
	apt-get update && \
	apt-get upgrade -y && \
	apt-get install --no-install-recommends -y libreoffice libicu-dev && \
	apt-get clean && \
	rm -rf /var/lib/apt/lists/* && \
# And intl for Nextcloud itself
	mv /usr/local/etc_dist /usr/local/etc && \
	docker-php-ext-install intl && \
	mv /usr/local/etc /usr/local/etc_dist
