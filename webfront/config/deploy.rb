set :default_stage, "staging"
set :stage_dir, "config/stages"

require 'capistrano/ext/multistage'

set :app_end, "webfront"
set :application, "webfront"
set :domain,      "alexandria.lighthouse.cs"
set :user,        "watchman"
set :deploy_to,   "/var/www/#{application}"
set :deploy_via,  :remote_cache_subfolder
set :deploy_subdir, "webfront"

set :repository,  "git@git.lighthouse.cs:lighthouse.git"
set :scm,         :git

ssh_options[:forward_agent] = true

set :use_sudo, false
default_run_options[:pty] = true

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server

set  :keep_releases,  5

logger.level = Logger::IMPORTANT

set :normalize_asset_timestamps, false

after "deploy:restart" do
    puts "--> Webfront was successfully deployed to ".green + "#{application_url}".yellow
end

after "deploy:finalize_update", "webfront"