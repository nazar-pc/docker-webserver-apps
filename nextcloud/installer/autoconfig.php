<?php
$AUTOCONFIG = [
	'directory'      => '/data/nginx/www/data',
	'dbtype'         => 'mysql',
	'dbname'         => 'nextcloud',
	'dbhost'         => 'mysql',
	'dbtableprefix'  => 'oc_',
	'dbuser'         => 'root',
	'dbpass'         => trim(file_get_contents('/data/mysql/root_password')),
	'memcache.local' => '\\OC\\Memcache\\APCu',
];
