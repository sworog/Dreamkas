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
  preferred_state "alpha"
  action :install
end

package "libpcre3-dev"
php_pear "apc" do
  action :install
end