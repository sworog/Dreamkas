set :default_stage, "staging"
set :stage_dir, "app/config/stages"

require 'capistrano/ext/multistage'

def current_branch
    `git branch`.match(/\* (\S+)\s/m)[1]\
end

set :branch, current_branch || "master" unless exists?(:branch)

puts "--> Branch ".yellow + "#{branch}".red + " will be used for deploy".yellow

set :host, branch unless exists?(:host)
set :application, "#{host}.api"
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

namespace :symfony do

    desc "Run custom command. Add '-s command=<command goes here>' option"
    task :console do
        prompt_with_default(:command, "list") unless exists?(:command)
        stream "sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} #{command} --env=#{symfony_env_prod}'"
    end

    namespace :doctrine do
        namespace :mongodb do
            namespace :schema do
                desc "Drop and create schema"
                task :recreate do
                    drop
                    create
                end
            end
        end
    end

    namespace :logs do
        task :default, :roles => :app, :except => { :no_release => true } do
            set :lines, '50' unless exists?(:lines)
            log = "#{stage}.log"
            run "#{try_sudo} tail -n #{lines} -f #{shared_path}/#{log_path}/#{log}" do |channel, stream, data|
              trap("INT") { puts 'Interupted'; exit 0; }
              puts
              puts "#{channel[:host]}: #{data}"
              break if stream == :err
            end
        end
    end

end

namespace :deploy do

    desc "Check & setup environment if needed"
    task :setup_if_needed, :roles => :app, :except => { :no_release => true } do
        begin
            check
        rescue Exception => error
            setup
        end
    end

    task :restart, :roles => :app, :except => { :no_release => true } do
        run "sudo service php5-fpm reload"
        capifony_pretty_print "--> Reloading PHP-FPM"
        capifony_puts_ok
    end
end

after "multistage:ensure" do
    set :application, "#{host}.api.#{stage}"
    set :deploy_to,   "/var/www/#{application}"
    set :symfony_env_prod, stage
    set :application_url, "http://#{application}.lighthouse.cs"
    puts "--> Application will be deployed to ".yellow + application_url.red
end

after "deploy:restart" do
    puts "--> API was successfully deployed to ".green + "http://#{application}.lighthouse.cs".yellow
end