FROM nazarpc/webserver:php-fpm
MAINTAINER Nazar Mokrynskyi <nazar@mokrynskyi.com>

# Set desired ownCloud version

RUN OWNCLOUD_VERSION=8.1.0 && \

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
COPY fix-permissions.sh /dist/fix-permissions.sh
COPY autoconfig.php /dist/owncloud/config/autoconfig.php

# At first we'll check if ownCloud already installed, if so - stop everything

CMD test ! -d /data/nginx/www/owncloud && \

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

	cp -a /dist/owncloud /data/nginx/www && \

# Create data directory

	mkdir /data/nginx/www/owncloud/data && \

# Fix permissions as recommended by ownCloud

	sh /dist/fix-permissions.sh && \

# Create DB table

	echo "CREATE DATABASE owncloud" | mysql -h mysql -u root -p$(cat /data/mysql/root_password)
