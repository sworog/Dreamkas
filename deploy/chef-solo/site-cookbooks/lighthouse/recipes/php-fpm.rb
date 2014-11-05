# encoding: utf-8

#############################################
# php
#############################################

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

link "/etc/php5/fpm/conf.d/20-mongo.ini" do
  to "/etc/php5/mods-available/mongo.ini"
end