# nginx
template "#{node.nginx.dir}/sites-available/lighthouse_backend.conf" do
  source "backend.conf.erb"
  mode "0644"
end

template "#{node.nginx.dir}/sites-available/lighthouse_webfront.conf" do
  source "webfront.conf.erb"
  mode "0644"
end

nginx_site "lighthouse_backend.conf"
nginx_site "lighthouse_webfront.conf"

# cron

include_recipe "cron"

cookbook_file "lh-run-command.sh" do
  path "/usr/local/bin/lh-run-command.sh"
  action :create
  owner "watchman"
  mode "0744"
end

cron_d "import_sales" do
  minute  "*/1"
  command "/usr/local/bin/lh-run-commmand.sh 'ligthouse:import:sales'"
  user    "watchman"
end

cron_d "recal_average_purchase_price" do
  minute  20
  houre   4
  command "/usr/local/bin/lh-run-commmand.sh 'ligthouse:recalculate-average-purchase-price'"
  user    "watchman"
end

