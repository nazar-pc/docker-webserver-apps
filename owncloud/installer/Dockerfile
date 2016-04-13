FROM nazarpc/webserver:php-fpm
MAINTAINER Nazar Mokrynskyi <nazar@mokrynskyi.com>

# Set desired ownCloud version

RUN OWNCLOUD_VERSION=9.0.1 && \

# We'll need bzip2 to extract downloaded ownCloud distributive and mysql-client to create DB

	apt-get update && \
	apt-get upgrade -y && \
	apt-get install --no-install-recommends -y bzip2 mysql-client && \
	apt-get clean && \
	rm -rf /var/lib/apt/lists/* && \

# Create directory where distributive will be stored

	mkdir /dist && \
	cd /dist && \

# Download and extract ownCloud

	curl https://download.owncloud.org/community/owncloud-${OWNCLOUD_VERSION}.tar.bz2 | tar --extract --bzip2 --file -

COPY mysql.cnf /dist/mysql.cnf
COPY nginx.conf /dist/nginx.conf
COPY strict-permissions.sh /dist/strict-permissions.sh
COPY relax-permissions.sh /dist/relax-permissions.sh
COPY autoconfig.php /dist/owncloud/config/autoconfig.php

# At first we'll check if ownCloud already installed, if so - stop everything

CMD test ! -f /data/nginx/www/occ && \

	echo 'Waiting for Nginx and MariaDB to get ready...' && \

# Wait for Nginx container to initialize

	while test ! -d /data/nginx; do sleep 1; done && \

# Wait for MariaDB container to start (120 seconds maximum)

	mysqladmin --host=mysql --user=root --password=$(cat /data/mysql/root_password) --wait=120 ping && \

# Copy default Nginx config

	cp /dist/nginx.conf /data/nginx/config/conf.d/default.conf && \

# Copy additional MySQL config

	cp /dist/mysql.cnf /data/mysql/config/conf.d/git.cnf && \

# Configure HTTPS

	( \
		test ! -f /dist/key || \
		( \
			cp /dist/crt /data/nginx/crt && \
			cp /dist/key /data/nginx/key && \
			sed -i 's/#SSL//g' /data/nginx/config/conf.d/default.conf \
		) \
	) && \

# Copy ownCloud files

	cp -a /dist/owncloud/. /data/nginx/www && \
	cp /dist/strict-permissions.sh /data/nginx/strict-permissions.sh && \
	cp /dist/relax-permissions.sh /data/nginx/relax-permissions.sh && \

# Create data directory

	mkdir /data/nginx/www/data && \

# Fix permissions as recommended by ownCloud

	sh /dist/strict-permissions.sh && \

# Create DB table

	echo "CREATE DATABASE owncloud" | mysql -h mysql -u root -p$(cat /data/mysql/root_password) && \

	echo 'ownCloud installed, reboot mariadb and nginx instances in order to apply configuration changes'
