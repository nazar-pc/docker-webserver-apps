FROM nazarpc/webserver:php-fpm-v1
MAINTAINER Nazar Mokrynskyi <nazar@mokrynskyi.com>

# Set desired NextCloud version

RUN NEXTCLOUD=15.0.4 && \
# We'll need bzip2 to extract downloaded NextCloud distributive and mysql-client to create DB
	apt-get update && \
	apt-get upgrade -y && \
	apt-get install --no-install-recommends -y bzip2 mysql-client && \
	apt-get clean && \
	rm -rf /var/lib/apt/lists/* && \
# Create directory where distributive will be stored
	mkdir /dist && \
	cd /dist && \
# Download and extract NextCloud
	curl https://download.nextcloud.com/server/releases/nextcloud-${NEXTCLOUD}.tar.bz2 | tar --extract --bzip2 --file -
COPY mysql.cnf /dist/mysql.cnf
COPY nginx.conf /dist/nginx.conf
COPY autoconfig.php /dist/nextcloud/config/autoconfig.php

ENTRYPOINT []

# At first we'll check if NextCloud already installed, if so - stop everything

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

# Copy NextCloud files

	cp -a /dist/nextcloud/. /data/nginx/www && \

# Create data directory

	mkdir /data/nginx/www/data && \

# Fix permissions

	chown -R git:git /data/nginx/www/ && \

# Create DB table

	echo "CREATE DATABASE nextcloud" | mysql -h mysql -u root -p$(cat /data/mysql/root_password) && \

	echo 'NextCloud installed, reboot mariadb and nginx instances in order to apply configuration changes'
