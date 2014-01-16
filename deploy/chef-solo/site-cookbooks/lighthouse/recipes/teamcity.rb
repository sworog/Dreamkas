# encoding: utf-8

#############################################
# users
#############################################
user "teamcity" do
  :create
  shell "/bin/bash"
  home "/home/teamcity"
  supports :manage_home => true
end

#############################################
# rights
#############################################
execute "change_rights" do
  command "chown teamcity:teamcity /opt/TeamCity -R"
  action :run
end

#############################################
# Start TeamCity Service
#############################################
cookbook_file "/etc/init/teamcity-server.conf" do
  backup false
  source "teamcity-server.conf"
  action :create
  notifies :start, "service[teamcity-server]", :immediately
end

service "teamcity-server" do
  provider Chef::Provider::Service::Upstart
  action :restart
end

#############################################
# nginx
#############################################
cookbook_file "teamcity_nginx.conf" do
  path "#{node.nginx.dir}/sites-available/default"
  action :create
  mode "0644"
  owner "root"
  group "root"
end

nginx_site "default"
