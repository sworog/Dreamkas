package "pptp-linux"

cookbook_file "vpn_chap_secrets" do
  path "/etc/ppp/chap-secrets"
end

cookbook_file "vpn_peers_crystal" do
  path "/etc/ppp/peers/crystal"
end

cookbook_file "vpn_up_d_crystal" do
  path "/etc/ppp/ip-up.d/crystal"
  mode "0755"
end

cookbook_file "vpn_check_up.sh" do
  path "/usr/local/bin/vpn_check_up.sh"
  mode "0744"
end

cookbook_file "vpn_cron_check_up" do
  path "/etc/cron.d/vpn_check_up"
  action :create
  mode "0744"
  owner "root"
end

bash "vpn_check_up" do
  interpreter "bash"
  code "/usr/local/bin/vpn_check_up.sh"
end
