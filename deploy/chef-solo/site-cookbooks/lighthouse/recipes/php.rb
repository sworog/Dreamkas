# encoding: utf-8

#############################################
# php
#############################################
package "make"

template "/etc/php5/fpm/php.ini" do
	source node['php']['ini']['template']
	cookbook node['php']['ini']['cookbook']
	unless platform?('windows')
		owner 'root'
		group 'root'
		mode '0644'
	end
	variables(:directives => node['php']['directives'])
end

package "php5-intl" do
  action :install
end

package "php5-curl" do
  action :install
end

php_pear "mongo" do
  preferred_state "alpha"
  action :install
end

link "/etc/php5/cli/conf.d/mongo.ini" do
	to "/etc/php5/mods-available/mongo.ini"
end

link "/etc/php5/fpm/conf.d/mongo.ini" do
	to "/etc/php5/mods-available/mongo.ini"
end

package "php5-apcu"