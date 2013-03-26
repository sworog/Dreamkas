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

    task :setup_parameters, :roles => :app, :except => { :no_release => true } do
        origin_file = "app/config/parameters.yml"
        destination_file = shared_path + "/app/config/parameters.yml" # Notice the shared_path

        try_sudo "mkdir -p #{File.dirname(destination_file)}"
        top.upload(origin_file, destination_file)

        database_name = application
        run "sed -r -i 's/(database_name:\s+)\S+/\1#{database_name}/g' #{destination_file}"
    end

end

after "multistage:ensure", "deploy:init"
after "deploy:setup", "deploy:setup_parameters"