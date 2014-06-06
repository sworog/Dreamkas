set :default_stage, "staging"
set :stage_dir, "app/config/stages"

require 'capistrano/ext/multistage'

set :app_end, "api"
set :application, "api"
set :domain,      "coquille.lighthouse.pro" unless exists?(:domain)
set :deploy_to_base,   "/var/www/"
set :deploy_to,   "#{deploy_to_base}#{application}"
set :deploy_via,  :remote_cache_subfolder
set :deploy_subdir, "backend"
set :app_path,    "app"
set :web_path,    "web"
set :user,        "watchman"
set :shared_files, [app_path + "/config/parameters.yml"]

set :repository,  "git@github.com:crystalservice/lighthouse.git"
set :scm,         :git

ssh_options[:forward_agent] = true

set :use_sudo, false
default_run_options[:pty] = true

set :use_composer, true
set :update_vendors, false
set :composer_bin, "/usr/bin/composer"

set :use_set_permissions,   false

set :api_clients, [
    {"public_id" => "autotests", "secret" => "secret"},
    {"public_id" => "webfront", "secret" => "secret"}
]

set :api_users, [
    {"email" => "watchman@lighthouse.pro",  "userpass" => "lighthouse", "userrole" => "ROLE_ADMINISTRATOR"},
    {"email" => "owner@lighthouse.pro",     "userpass" => "lighthouse"},
]

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  5

logger.level = Logger::IMPORTANT

#before "deploy", "deploy:vpn"

#before "deploy:restart", "deploy:php:reload"
#before "deploy:restart", "deploy:supervisor:restart"

after "deploy:restart" do
    puts "--> API was successfully deployed to ".green + "#{application_url}".yellow
end

