#############################################
# users
#############################################
user "teamcity" do
  :create
  shell "/bin/bash"
  home "/home/teamcity"
  supports :manage_home => true
end