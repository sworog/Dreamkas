set :application, "LightHouse"
set :domain,      "cs-watchman"
set :deploy_to,   "/var/www/lighthouse"
set :deploy_via, :copy_subdir
set :deploy_subdir, "backend"
set :app_path,    "app"
set :web_path,    "web"
set :user,        "watchman"

set :repository,  "git@cs-watchman:lighthouse.git"
set :scm,         :git

ssh_options[:forward_agent] = true

#set :deploy_via, :remote_cache
set :use_sudo, false
default_run_options[:pty] = true

set :use_composer, true
set :update_vendors, true

set :use_set_permissions,   true
set :permission_method,   :acl

set :model_manager, "doctrine"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  5

logger.level = Logger::TRACE