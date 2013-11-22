#############################################
# php
#############################################

php_pear "xdebug" do
  # Specify that xdebug.so must be loaded as a zend extension
  zend_extensions ['xdebug.so']
  action :install
end

php_pear_channel "badoo.github.com" do
  action :discover
end

php_pear "PHPUnit_TestListener_TeamCity" do
  action :install
  channel "badoo.github.com"
  preferred_state "beta"
end

cookbook_file "TestListener.php" do
  action :create
  path "/usr/share/php/PHPUnit/Extensions/TeamCity/TestListener.php"
  mode "0664"
end

package "parallel"
