FROM nazarpc/webserver:php-fpm
MAINTAINER Nazar Mokrynskyi <nazar@mokrynskyi.com>

# Rename PHP-FPM config directory to original name to allow module installation

RUN mv /usr/local/etc_dist /usr/local/etc && \

# Also temporary change permissions

	chown -R root:root /usr/local/etc && \

# Install MySQL extension

	docker-php-ext-install mysql && \

# Rename PHP-FPM config directory back

	mv /usr/local/etc /usr/local/etc_dist && \

# And revert permissions changes

	chown -R git:git /usr/local/etc_dist
