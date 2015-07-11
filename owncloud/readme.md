# [ownCloud](https://owncloud.org/) - A personal cloud which runs on your own server

IMPORTANT: there are two packages here:
* nazarpc/webserver-apps:owncloud-installer
* nazarpc/webserver-apps:owncloud-php-fpm

IMPORTANT: use `nazarpc/webserver-apps:owncloud-php-fpm` instead of `nazarpc/webserver:php-fpm` in your `docker-compose.yml` because:
* it additionally contains MySQL extension since ownCloud doesn't support neither MySQLi nor PDO
* also it contains pre-installed LibreOffice, which is required for Documents app for MSO documents support

### HTTP connection:
```
cd /example.com
docker run --rm -it --volumes-from example.com --link examplecom_db_1:mysql nazarpc/webserver-apps:owncloud-installer
docker-compose restart db nginx
```
Were:
* `/example.com` is directory where `docker-compose.yml` is located
* `--volumes-from example.com` means that `example.com` is your data-only container
* `--link examplecom_db_1:mysql` means that `examplecom_db_1` is database container created by `docker-compose`

### If you plan to have HTTPS connection, use following command instead:
```
cd /example.com
docker run --rm -it --volumes-from example.com --link example_com_db_1:mysql -v /ssl.crt:/dist/crt -v /ssl.key:/dist/key nazarpc/webserver-apps:owncloud-installer
docker-compose restart db nginx
```
It is similar to previous with addition of SSL/TLS keys, needed for HTTPS  connection, just replace `/ssl.crt` and `/ssl.key` with actual paths to appropriate files on server
