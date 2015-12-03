FROM nazarpc/webserver:php-fpm
MAINTAINER Nazar Mokrynskyi <nazar@mokrynskyi.com>

RUN \

# Copy PHP-FPM configs to original name to allow module installation

	cp -a /usr/local/etc_dist/* /usr/local/etc/ && \

# Install MySQL extension

	docker-php-ext-install mysql && \

# Copy PHP-FPM configs back

	cp -a /usr/local/etc/* /usr/local/etc_dist/ && \

# Fix permissions for new files

	chown -R git:git /usr/local/etc_dist && \

# We'll libreoffice for Documents app

	apt-get update && \
	apt-get upgrade -y && \
	apt-get install --no-install-recommends -y libreoffice && \
	apt-get clean && \
	rm -rf /var/lib/apt/lists/*
