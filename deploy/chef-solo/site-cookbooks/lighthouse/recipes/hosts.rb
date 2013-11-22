#############################################
# nginx
#############################################
directory "/var/www" do
  action :create
  owner "watchman"
  group "watchman"
end

directory "/var/www/nginx" do
  action :create
  owner "watchman"
  group "watchman"
end

cookbook_file "nginx_404.html" do
  path "/var/www/nginx/404.html"
  action :create
  owner "watchman"
  mode "0644"
end

cookbook_file "favicon.ico" do
  path "/var/www/nginx/favicon.ico"
  action :create
  owner "watchman"
  mode "0644"
end

template "#{node.nginx.dir}/sites-available/lighthouse.conf" do
  source "nginx/lighthouse.conf.erb"
  mode "0644"
end

nginx_site "lighthouse.conf"

#############################################
# cron
#############################################
cookbook_file "cron_lh-run-command.sh" do
  path "/usr/local/bin/lh-run-command.sh"
  action :create
  owner "watchman"
  mode "0744"
end

cookbook_file "cron_lighthouse" do
  path "/etc/cron.d/lighthouse"
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
