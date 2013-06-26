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

        desc "Tail symfony log according to environment"
        task :default, :roles => :app, :except => { :no_release => true } do
            set :lines, '50' unless exists?(:lines)
            log = "#{stage}.log"
            run "#{try_sudo} tail -n #{lines} -f #{shared_path}/#{log_path}/#{log}" do |channel, stream, data|
              trap("INT") { puts 'Interrupted'; exit 0; }
              puts
              puts "#{channel[:host]}: #{data}"
              break if stream == :err
            end
        end

    end

    namespace :parameters do

        desc "Setup parameters: upload parameters.yml and rename database"
        task :setup, :roles => :app, :except => { :no_release => true } do
            upload
            rename_database_name
        end

        desc "Upload current parameters.yml to shared folder"
        task :upload, :roles => :app, :except => { :no_release => true } do
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

    namespace :auth do
        namespace :client do

            def create_auth_client(secret, public_id)
                capture "#{try_sudo} sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} lighthouse:auth:client:create #{secret} #{public_id} --env=#{symfony_env_prod}'", :once => true
            end

            desc "Create API client, required: -S public_id=<..> -S secret=<..>"
            task :create, :roles => :app, :except => { :no_release => true } do
                puts "--> Creating client"
                raise "secret should be provided by -S secret=.." unless exists?(:secret)
                raise "public_id should be provided by -S public_id=.." unless exists?(:public_id)
                puts create_auth_client(secret, public_id)
            end

            desc "Create default API clients provided in :api_clients variable"
            task :create_default, :roles => :app, :except => { :no_release => true } do
                puts "--> Creating default clients"
                api_clients.each do |api_client|
                    puts "--> Creating client " + api_client['public_id'].green
                    puts create_auth_client(api_client['secret'], api_client['public_id'])
                end
            end

            desc "List API clients"
            task :list, :roles => :app, :except => { :no_release => true } do
                puts "--> List auth clients"
                puts capture "#{try_sudo} sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} lighthouse:auth:client:list --env=#{symfony_env_prod}'", :once => true
            end
        end
    end

    namespace :user do

        def create_api_user(username, userpass, userrole)
            capture "cd #{latest_release} && #{php_bin} #{symfony_console} lighthouse:user:create #{username} #{userpass} #{userrole} --env=#{symfony_env_prod}"
        end

        desc "Create user, required: -S username=<..> -S userpass=<..>, optional: -S userrole=<..> (administrator by default)"
        task :create, :roles => :app, :except => { :no_release => true } do
            puts "--> Creating user"
            raise "username should be provided by -S username=.." unless exists?(:username)
            raise "userpass should be provided by -S userpass=.." unless exists?(:userpass)
            set :userrole, "" unless exists?(:userrole)
            puts create_api_user(username, userpass, userrole)
            capifony_puts_ok
        end

        desc "Create default users"
        task :create_default, :roles => :app, :except => { :no_release => true } do
            transaction do
            on_rollback do
                puts "--> Failed to create user".red
            end
            api_users.each do |api_user|
                puts "--> Creating user " + api_user['username'].green
                puts create_api_user(api_user['username'], api_user['userpass'], api_user['userrole'])
            end
            end
        end
    end

    namespace :env do
        desc "Reacreate db, create default clients and users"
        task :init, :roles => :app, :except => { :no_release => true } do
            doctrine.mongodb.schema.recreate
            user.create_default
            auth.client.create_default
        end
    end
end