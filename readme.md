##Demo OAuth Laravel 4

#Config apache

<VirtualHost *:80>
	DocumentRoot /Users/your/Sites/demo-l4-oauth/public
	<Directory "/Users/your/Sites/demo-l4-oauth/public">
		Options FollowSymLinks Indexes MultiViews
		AllowOverride All
	</Directory>	
	ServerName oauth.demo.dev
	ServerAlias app*.dev
</VirtualHost>

#Config /etc/host
127.0.0.1   oauth.demo.dev
127.0.0.1   app1.dev
127.0.0.1   app2.dev
127.0.0.1   app3.dev

#Mysql
create dataase oauth
mysql -uroot -p oauth < oauth.sql

#Composer
composer install

#Parche auto-aproved apps

vendor/lucadegasperi/oauth2-server-laravel/src/LucaDegasperi/OAuth2Server/Repositories/FluentClient.php

Add line in 45: 'auto' =>  $result->auto,

#Test user
test1 test
test2 test

