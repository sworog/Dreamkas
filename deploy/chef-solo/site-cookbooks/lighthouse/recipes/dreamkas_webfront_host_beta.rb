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
  path "/var/www/nginx/favicon.ico"
  action :create
  owner "watchman"
  group "www-data"
  mode "0644"
end

cookbook_file "nginx/dreamkas_beta_webfront.conf" do
  source "nginx/dreamkas_beta_webfront.conf"
  path "/etc/nginx/sites-available/dreamkas_beta_webfront.conf"
  action :create
end

nginx_site "dreamkas_beta_webfront.conf"