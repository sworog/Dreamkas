namespace :deploy do

    desc "Check & setup environment if needed"
    task :setup_if_needed, :roles => :app, :except => { :no_release => true } do
        begin
            check
        rescue Exception => error
            setup
        end
    end

    desc "Reload PHP-FPM"
    task :reload_php, :roles => :app, :except => { :no_release => true } do
        run "#{sudo} service php5-fpm reload"
        capifony_pretty_print "--> Reloading PHP-FPM"
        capifony_puts_ok
    end

    desc "Init deploy configuration using current git branch"
    task :init do

        current_branch = `git rev-parse --abbrev-ref HEAD`.delete("\n")

        set :app_end, 'api' unless exists?(:app_end)
        set :branch, current_branch || "master" unless exists?(:branch)
        set :host, branch unless exists?(:host)

        set :application, "#{host}.#{stage}.#{app_end}"
        set :deploy_to,   "/var/www/#{application}"
        set :symfony_env_prod, stage
        set :application_url, "http://#{application}.lighthouse.cs"

        puts "--> Branch ".yellow + "#{branch}".red + " will be used for deploy".yellow
        puts "--> Application will be deployed to ".yellow + application_url.red
    end

end

after "multistage:ensure", "deploy:init"