set :default_stage, "staging"
set :stage_dir, "app/config/stages"

require 'capistrano/ext/multistage'

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
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  5

logger.level = Logger::TRACE

namespace :symfony do
    desc "Clears database"
    namespace :lighthouse do
        namespace :database do
            desc "Clear database"
            task :clear, :roles => :app, :except => { :no_release => true } do
                run "cd #{latest_release} && #{php_bin} #{symfony_console} lighthouse:database:clear --env=#{symfony_env_prod}"
            end
        end

        desc "Run custom command. Add '-s command=<command goes here>' option"
        task :run do
            prompt_with_default(:command, "list") unless exists?(:command)
            stream "sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} #{command} --env=#{symfony_env_prod}'"
        end
    end

end