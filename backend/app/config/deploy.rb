set :default_stage, "staging"
set :stage_dir, "app/config/stages"

require 'capistrano/ext/multistage'

set :app_end, "api"
set :application, "api"
set :domain,      "coquille.lighthouse.cs"
set :deploy_to_base,   "/var/www/"
set :deploy_to,   "#{deploy_to_base}#{application}"
set :deploy_via,  :remote_cache_subfolder
set :deploy_subdir, "backend"
set :app_path,    "app"
set :web_path,    "web"
set :user,        "watchman"
set :shared_files, [app_path + "/config/parameters.yml"]

set :repository,  "git@git.lighthouse.cs:lighthouse.git"
set :scm,         :git

ssh_options[:forward_agent] = true

set :use_sudo, false
default_run_options[:pty] = true

set :use_composer, true
set :update_vendors, true

set :use_set_permissions,   false

set :api_users, [
    {"username" => "watchman", "userpass" => "lighthouse", "userrole" => "administrator"},
    {"username" => "administrator", "userpass" => "lighthouse", "userrole" => "administrator"},
    {"username" => "commercialManager", "userpass" => "lighthouse", "userrole" => "commercialManager"},
    {"username" => "storeManager", "userpass" => "lighthouse", "userrole" => "storeManager"},
    {"username" => "departmentManager", "userpass" => "lighthouse", "userrole" => "departmentManager"},
    {"username" => "administratorApi", "userpass" => "lighthouse", "userrole" => "administrator"},
    {"username" => "commercialManagerApi", "userpass" => "lighthouse", "userrole" => "commercialManager"},
    {"username" => "storeManagerApi", "userpass" => "lighthouse", "userrole" => "storeManager"},
    {"username" => "departmentManagerApi", "userpass" => "lighthouse", "userrole" => "departmentManager"}
]

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  5

logger.level = Logger::IMPORTANT

after "deploy:setup", "symfony:parameters:setup"

before "deploy:restart", "deploy:reload_php"

after "deploy:restart" do
    puts "--> API was successfully deployed to ".green + "#{application_url}".yellow
end

