##Binter OAuth

#Config sample apache

<VirtualHost *:80>
	DocumentRoot /Users/inventia/Sites/binter-oauth/public
	<Directory "/Users/inventia/Sites/binter-oauth/public">
		Options FollowSymLinks Indexes MultiViews
		AllowOverride All
	</Directory>	
	ServerName oauth.bintercanarias.dev
	ServerAlias app*.dev
</VirtualHost>

#Composer
composer install