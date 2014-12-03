# encoding: utf-8

#############################################
# php
#############################################
package "make"

package "php5-intl" do
  action :install
end

package "php5-curl" do
  action :install
end

php_pear "mongo" do
  action :install
end

puts node

if platform?('ubuntu')

  if Chef::VersionConstraint.new('> 12.04').include?(node[:platform_version])
    link "/etc/php5/cli/conf.d/20-mongo.ini" do
      to "/etc/php5/mods-available/mongo.ini"
    end

    package "php5-apcu"
  else
    package "php-apc"
    #php_pear "apc" do
    #  action :install
    #end
  end
end