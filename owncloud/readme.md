# ownCloud

### HTTP connection:
```
docker run --rm -it --volumes-from example.com --link examplecom_db_1:mysql nazarpc/webserver-apps:owncloud-installer
docker-compose restart db nginx
```
Were:
* `--volumes-from example.com` means that `example.com` is your data-only container
* `--link examplecom_db_1:mysql` means that `examplecom_db_1` is database container created by `docker-compose`

### If you plan to have HTTPS connection, use following command instead:
```
docker run --rm -it --volumes-from example.com --link example_com_db_1:mysql -v /ssl.crt:/dist/crt -v /ssl.key:/dist/key nazarpc/webserver-apps:owncloud-installer
docker-compose restart db nginx
```
It is similar to previous with addition of SSL/TLS keys, heeded for HTTPS  connection, just replace `/ssl.crt` and `/ssl.key` with actual paths to appropriate files on server
