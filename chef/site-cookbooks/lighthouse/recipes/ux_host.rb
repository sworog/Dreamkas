#############################################
# users
#############################################
directory "/var/www" do
  action :create
  owner "watchman"
  group "watchman"
end

user "ux" do
  :create
  shell "/bin/sh"
  home "/var/www/ux"
  supports :manage_home => true
end

#############################################
# ssh config
#############################################
directory "/var/www/ux/.ssh" do
  action :create
  owner "ux"
  group "ux"
end

cookbook_file "ux/authorized_keys" do
  source "ux/authorized_keys"
  path "/var/www/ux/.ssh/authorized_keys"
  owner "ux"
  group "ux"
end

#############################################
# nginx
#############################################
directory "/var/www/ux" do
  action :create
  owner "ux"
  group "ux"
end

cookbook_file "ux/nginx_host_ux.conf" do
  source "ux/nginx_host_ux.conf"
  path "#{node.nginx.dir}/sites-available/ux.conf"
  mode "0644"
end

nginx_site "ux.conf"
