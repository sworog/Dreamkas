#############################################
# nginx
#############################################
directory "/var/www" do
  action :create
  owner "watchman"
  group "www-data"
end

directory "/var/www/nginx" do
  action :create
  owner "watchman"
  group "www-data"
end

cookbook_file "nginx/favicon.ico" do
  source "nginx/favicon.ico"
  path "/var/www/nginx/favicon.ico"
  action :create
  owner "watchman"
  group "www-data"
  mode "0644"
end

cookbook_file "nginx/dreamkas_beta_api.conf" do
  source "nginx/dreamkas_beta_api.conf"
  path "/etc/nginx/sites-available/dreamkas_beta_api.conf"
  action :create
end

nginx_site "dreamkas_beta_api.conf"

#############################################
# cron
#############################################

cookbook_file "cron/beta-api-dreamkas" do
  source "cron/beta-api-dreamkas"
  path "/etc/cron.d/beta-api-dreamkas"
  action :create
  mode "0644"
  owner "root"
end

#############################################
#supervisord
#############################################
package "supervisor"

service "supervisor" do
  reload_command "supervisorctl update"
end

cookbook_file "supervisord_init" do
  path "/etc/init.d/supervisor"
  action :create
  mode "0744"
  owner "root"
  group "root"
end

cookbook_file "supervisord_default" do
  path "/etc/default/supervisor"
  action :create
  mode "0644"
  owner "root"
  group "root"
end
