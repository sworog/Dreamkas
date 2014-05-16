# encoding: utf-8

#############################################
# admin
#############################################
case node["platform_family"]
when "debian"
  package "vim"
  package "htop"
  package "curl"
  package "git"
  package "mc"
  package "smbclient"
when "rhel"
  package "vim-common"
  package "mc"
  package "curl"
end

#############################################
# users
#############################################
user "watchman" do
  :create
  password "$1$Cgi1/uGL$zUZsBGlvxDjrX0YpahCvq/"
  shell "/bin/bash"
  home "/home/watchman"
  supports :manage_home => true
end
case node["platform_family"]
when "debian"
  cookbook_file "sudo_watchman" do
    path "/etc/sudoers.d/watchman"
    owner "root"
    group "root"
    mode 0440
  end
when "rhel"
  cookbook_file "/tmp/parms_to_append.conf" do
    source "sudo_watchman"
  end

  bash "append_to_config" do
    user "root"
    code <<-EOF
       cat /tmp/parms_to_append.conf >> /etc/sudoers
       rm /tmp/parms_to_append.conf
    EOF
    not_if "grep -q watchman /etc/sudoers"
  end
end


#############################################
# system
#############################################
cookbook_file "inputrc" do
  path "/etc/inputrc"
  owner "root"
  group "root"
end

directory "/home/watchman/.ssh" do
  action :create
  owner "watchman"
  group "watchman"
end

cookbook_file "ssh_rsa_privite_key" do
  path "/home/watchman/.ssh/id_rsa"
  owner "watchman"
  group "watchman"
  mode "0600"
end

cookbook_file "authorized_keys" do
  path "/home/watchman/.ssh/authorized_keys"
  owner "watchman"
  group "watchman"
end
