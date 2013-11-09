set :default_stage, "staging"
set :stage_dir, "config/stages"

require 'capistrano/ext/multistage'

set :app_end, "webfront"
set :application, "webfront"
set :domain,      "coquille.lighthouse.cs"
set :user,        "watchman"
set :deploy_to_base,   "/var/www/"
set :deploy_to,   "{#deploy_to_base}#{application}"
set :deploy_via,  :remote_cache_subfolder
set :deploy_subdir, "webfront/web"

set :repository,  "git@bitbucket.org:lhcs/lighthouse.git"
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

namespace :deploy do
    namespace :remove do
        task :default, :roles => :app, :except => { :no_release => true } do
            if Capistrano::CLI.ui.ask("Are you sure drop " + application_url.yellow + " (y/n)") == 'y'
                host
            end
        end
    end
end

def capifony_pretty_print(msg)
    if logger.level == Capistrano::Logger::IMPORTANT
        pretty_errors

        msg = msg.slice(0, 57)
        msg << '.' * (60 - msg.size)
        print msg
    else
        puts msg.green
    end
end

def capifony_puts_ok
    if logger.level == Capistrano::Logger::IMPORTANT && !$error
        puts '✔'.green
    end

    $error = false
end

def pretty_errors
    if !$pretty_errors_defined
        $pretty_errors_defined = true

        class << $stderr
            @@firstLine = true
            alias _write write

            def write(s)
                if @@firstLine
                    s = '✘' << "\n" << s
                    @@firstLine = false
                end

                _write(s.red)
                $error = true
            end
        end
    end
end