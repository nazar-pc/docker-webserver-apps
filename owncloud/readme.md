# [ownCloud](https://owncloud.org/) - A personal cloud which runs on your own server

There are two packages here:
* nazarpc/webserver-apps:owncloud-installer - One-time ownCloud installer
* nazarpc/webserver-apps:owncloud-php-fpm - Modified nazarpc/webserver:php-fpm with MySQL extension and LibreOffice

Create directory for your website, it will contain `docker-compose.yml` file and potentially more files you'll need:
```
mkdir example.com
cd example.com
```

Now create `docker-compose.yml` inside with following contents:

```yml
cron:
  image: nazarpc/webserver-apps:owncloud-php-fpm
  environment:
    - TERM=xterm
  working_dir: /data/nginx/www/owncloud
  command: watch --no-title --interval 600 "test ! -f config/autoconfig.php && su git -c 'php occ background:cron' && su git -c 'php -f cron.php > /dev/null 2>&1'"
  links:
    - mariadb:mysql
  restart: always
  volumes_from:
    - data

data:
  image: nazarpc/webserver:data
  volumes_from:
    - example.com

logrotate:
  image: nazarpc/webserver:logrotate
  restart: always
  volumes_from:
    - data

mariadb:
  image: nazarpc/webserver:mariadb
  restart: always
  volumes_from:
    - data

nginx:
  image: nazarpc/webserver:nginx
  links:
    - php
#  ports:
#    - {ip where to bind}:{port on localhost where to bind}:80
  restart: always
  volumes_from:
    - data

# NOTE: this container is needed only once, so you can remove or comment-out it after installation
installer:
  image: nazarpc/webserver-apps:owncloud-installer
  links:
    - mariadb:mysql
  volumes_from:
    - data
# Uncomment following lines for HTTPS setup, correct /ssl.crt and /ssl.key accordingly to your full paths to SSL/TLS certificates on host
#  volumes:
#    - /ssl.crt:/dist/crt:ro
#    - /ssl.key:/dist/key:ro

# NOTE: we use modified image based on nazarpc/webserver:php-fpm with MySQL extension and LibreOffice pre-installed
php:
  image: nazarpc/webserver-apps:owncloud-php-fpm
  links:
    - mariadb:mysql
  restart: always
  volumes_from:
    - data

#phpmyadmin:
#  image: nazarpc/phpmyadmin
#  links:
#    - mariadb:mysql
#  restart: always
#  ports:
#    - {ip where to bind}:{port on localhost where to bind}:80

ssh:
  image: nazarpc/webserver:ssh
  restart: always
  volumes_from:
    - data
#  ports:
#    - {ip where to bind}:{port on localhost where to bind}:22
```

Now customize it as you like.

When you're done with editing:
```
docker-compose up -d
docker-compose restart mariadb nginx
```

Restart is needed to apply changed MariaDB and Nginx configurations, done by installer.

Now go to Web UI, enter login and password for ownCloud administrator.
That is it, you have ownCloud up and running.

Go to [WebServer repository](https://github.com/nazar-pc/docker-webserver) for details about backups, upgrade process and other things since they are the same (do not forget that here we use more packages and different set of them, so you to pull all images accordingly).
ownCloud itself can be upgraded from Web UI or through CLI, follow official guide according to your ownCloud version.
