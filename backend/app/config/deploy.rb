set :default_stage, "staging"
set :stage_dir, "app/config/stages"

require 'capistrano/ext/multistage'

set :app_end, "api"
set :application, "api"
set :domain,      "alexandria.lighthouse.cs"
set :deploy_to,   "/var/www/#{application}"
set :deploy_via,  :remote_cache_subfolder
set :deploy_subdir, "backend"
set :app_path,    "app"
set :web_path,    "web"
set :user,        "watchman"

set :repository,  "git@git.lighthouse.cs:lighthouse.git"
set :scm,         :git

ssh_options[:forward_agent] = true

set :use_sudo, false
default_run_options[:pty] = true

set :use_composer, true
set :update_vendors, true

set :use_set_permissions,   false

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  5

logger.level = Logger::IMPORTANT

after "multistage:ensure" do
    def current_branch
        `git branch`.match(/\* (\S+)\s/m)[1]\
    end

    set :branch, current_branch || "master" unless exists?(:branch)
    set :host, branch unless exists?(:host)

    set :application, "#{host}.#{stage}.api"
    set :deploy_to,   "/var/www/#{application}"
    set :symfony_env_prod, stage
    set :application_url, "http://#{application}.lighthouse.cs"
    puts "--> Branch ".yellow + "#{branch}".red + " will be used for deploy".yellow
    puts "--> Application will be deployed to ".yellow + application_url.red
end

before deploy:restart, deploy:reload_php

after "deploy:restart" do
    puts "--> API was successfully deployed to ".green + "#{application_url}".yellow
end