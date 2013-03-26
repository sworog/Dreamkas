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

    desc "Setup parameters: upload parameters.yml and rename database"
    task :setup_parameters, :roles => :app, :except => { :no_release => true } do
        upload_parameters
        rename_database_name
    end

    desc "Upload current parameters.yml to shared folder"
    task :upload_parameters, :roles => :app, :except => { :no_release => true } do
        origin_file = "app/config/parameters.yml"
        destination_file = shared_path + "/app/config/parameters.yml"

        try_sudo "mkdir -p #{File.dirname(destination_file)}"
        top.upload(origin_file, destination_file)
    end

    desc "Rename database_name in app/config/parameters.yml. Application name will be used (%branch.stage.env%) unless -S database_name=%database_name% argument is provided"
    task :rename_database_name, :roles => :app, :except => { :no_release => true } do
        set :database_name, application.gsub(/\./, '_') unless exists?(:database_name)
        puts "--> Database name in ".yellow + "parameters.yml".bold.yellow + " will be set to ".yellow + "#{database_name}".red
        destination_file = shared_path + "/app/config/parameters.yml"
        run "sed -r -i 's/^(\\s+database_name:\\s+).+$/\\1#{database_name}/g' #{destination_file}"
    end

end

after "multistage:ensure", "deploy:init"
after "deploy:setup", "deploy:setup_parameters"