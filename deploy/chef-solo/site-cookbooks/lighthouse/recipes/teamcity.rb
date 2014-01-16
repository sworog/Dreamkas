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

directory "/home/teamcity/.BuildServer/lib/jdbc" do
  action :create
  owner "teamcity"
  group "teamcity"
  recursive true
end

execute "change_rights2" do
  command "chown teamcity:teamcity /home/teamcity/.BuildServer -R"
  action :run
end

cookbook_file "/home/teamcity/.BuildServer/lib/jdbc/postgresql-9.3-1100.jdbc4.jar" do
  source "postgresql-9.3-1100.jdbc4.jar"
  action :create
  owner "teamcity"
  group "teamcity"
end

directory "/home/teamcity/.ssh" do
  action :create
  owner "teamcity"
  group "teamcity"
end

cookbook_file "ssh_rsa_privite_key" do
  path "/home/teamcity/.ssh/id_rsa"
  owner "teamcity"
  group "teamcity"
  mode "0600"
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
