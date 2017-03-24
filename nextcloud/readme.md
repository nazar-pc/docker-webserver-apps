# [NextCloud](https://nextcloud.com/) - A safe home for all your data

There are 3 images here:
* nazarpc/webserver-apps:nextcloud-installer - One-time NextCloud installer
* nazarpc/webserver-apps:nextcloud-php-fpm - Modified `nazarpc/webserver:php-fpm-v1` with LibreOffice
* nazarpc/webserver-apps:nextcloud-cron - the same as `nazarpc/webserver-apps:nextcloud-php-fpm` with defaults to run NextCloud cron command every 10 minutes


At first you'll need to create persistent data-only container that will store all files, databases, ssh keys and settings of all these things:
```
docker run --name example.com nazarpc/webserver:data-v1
```
This container will start and stop immediately, that is OK.

After this create directory for your website, it will contain docker-compose.yml file and potentially more files you'll need:
```
mkdir example.com
cd example.com
```

Now create `docker-compose.yml` inside with following contents:

```yml
version: '2'
services:
  cron:
    image: nazarpc/webserver-apps:nextcloud-cron
    links:
      - mariadb:mysql
    restart: always
    volumes_from:
      - data

  data:
    image: nazarpc/webserver:data-v1
    volumes_from:
      - example.com

  logrotate:
    image: nazarpc/webserver:logrotate-v1
    restart: always
    volumes_from:
      - data

  mariadb:
    image: nazarpc/webserver:mariadb-v1
    restart: always
    volumes_from:
      - data

  nginx:
    image: nazarpc/webserver:nginx-v1
    links:
      - php
#    ports:
#      - {ip where to bind}:{port on localhost where to bind}:80
    restart: always
    volumes_from:
      - data

#   NOTE: this container is needed only once, so you can remove or comment-out it after installation
  installer:
    image: nazarpc/webserver-apps:nextcloud-installer
    links:
      - mariadb:mysql
    volumes_from:
      - data
#   Uncomment following lines for HTTPS setup, correct /ssl.crt and /ssl.key accordingly to your full paths to SSL/TLS certificates on host
#    volumes:
#      - /ssl.crt:/dist/crt:ro
#      - /ssl.key:/dist/key:ro

#   NOTE: we use modified image based on nazarpc/webserver:php-fpm-v1 with LibreOffice pre-installed
  php:
    image: nazarpc/webserver-apps:nextcloud-php-fpm
    links:
      - mariadb:mysql
    restart: always
    volumes_from:
      - data

#  phpmyadmin:
#    image: nazarpc/webserver:phpmyadmin-v1
#    links:
#      - mariadb:mysql
#    restart: always
#    ports:
#      - {ip where to bind}:{port on localhost where to bind}:80

  ssh:
    image: nazarpc/webserver:ssh-v1
    restart: always
    volumes_from:
      - data
#    ports:
#      - {ip where to bind}:{port on localhost where to bind}:22
#    environment:
#      PUBLIC_KEY: '{your public SSH key}'
```

Now customize it as you like.

When you're done with editing:
```
docker-compose up -d
docker-compose logs -f installer
```
As soon as you see message that NextCloud was installed, reboot mariadb and nginx to apply configuration changes:
```
docker-compose restart mariadb nginx
```

Restart is needed to apply changed MariaDB and Nginx configurations, done by installer.

Now go to Web UI, enter login and password for NextCloud administrator.
That is it, you have NextCloud up and running.

Go to [WebServer repository](https://github.com/nazar-pc/docker-webserver) for details about backups, upgrade process and other things since they are the same (do not forget that here we use more packages and different set of them, so you to pull all images accordingly).
NextCloud itself can be upgraded from Web UI or through CLI, follow official guide according to your NextCloud version.

# NextCloud upgrade
When you want to upgrade NextCloud you'll need to relax permissions since they are intentionally strict for production installation.
So, before upgrade enter any of containers as root user from terminal on server, for instance, in such way:
```bash
docker exec -it examplecom_php_1 bash
```

Afterwards relax permissions temporary:
```bash
sh /data/nginx/relax-permissions.sh
```

Upgrade your instance in any way (from administration interface, for instance).
After upgrade apply strict permissions again:
```bash
sh /data/nginx/strict-permissions.sh
```
Do not forget to check changes in Nginx configuration and `/data/nginx/relax-permissions.sh`/`/data/nginx/strict-permissions.sh`, since important changes might occur in those occasionally.

That is it!
