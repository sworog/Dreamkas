set :default_stage, "staging"
set :stage_dir, "config/stages"

require 'capistrano/ext/multistage'

set :application, "webfront"
set :domain,      "cs-watchman"
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

logger.level = Logger::TRACE

set :normalize_asset_timestamps, false

namespace :lighthouse do

  task :default do
    build
    create_index
  end

  desc "Build webfront app"
  task :build, :roles => :app, :except => { :no_release => true } do
    run "cd #{latest_release} && ./mayak_build.cmd"
  end

  desc "Copy index_%env%.xml"
  task :create_index, :roles => :app, :except => { :no_release => true } do
    run "cd #{latest_release} && cp index.#{stage}.xml index.xml"
  end

end

after "deploy:finalize_update", "lighthouse"