require 'erb'

namespace :worker do

    after "deploy:update", "worker:update"
    after "deploy:remove:go", "worker:remove"

    set(:worker_conf_file) {"#{shared_path}/app/config/supervisor.conf"}
    set(:worker_symlink_file) {"/etc/supervisor/conf.d/#{application}.conf"}
    set(:worker_template) {File.read(File.join('app', 'deploy', 'template', 'supervisor.conf.erb'))}
    set(:worker_group) {"#{application}"}

    desc "Update worker"
    task :update,:roles => :app, :except => { :no_release => true } do
        config.rename
        config.create
        ctl.reread
        ctl.add
        ctl.restart
    end

    desc "Remove worker"
    task :remove,:roles => :app, :except => { :no_release => true } do
        ctl.stop
        config.remove
        ctl.remove
        ctl.reread
    end

    namespace :ctl do
        desc "Update workers config"
        task :reread, :roles => :app, :except => { :no_release => true } do
            capifony_pretty_print "--> Update workers"
            stream "#{sudo} supervisorctl reread"
            capifony_puts_ok
        end

        desc "Restart worker"
        task :restart, :roles => :app, :except => { :no_release => true } do
            capifony_pretty_print "--> Restart worker"
            stream "#{sudo} supervisorctl restart #{worker_group}:*"
            capifony_puts_ok
        end

        desc "Stop worker"
        task :stop, :roles => :app, :except => { :no_release => true } do
            capifony_pretty_print "--> Stop worker"
            stream "#{sudo} supervisorctl stop #{worker_group}:*"
            capifony_puts_ok
        end

        desc "Add worker group"
        task :add, :roles => :app, :except => { :no_release => true } do
            capifony_pretty_print "--> Add worker group"
            stream "#{sudo} supervisorctl add #{worker_group}"
            capifony_puts_ok
        end

        desc "Remove worker"
        task :remove, :roles => :app, :except => { :no_release => true } do
            capifony_pretty_print "--> Remove worker group"
            stream "#{sudo} supervisorctl remove #{worker_group}"
            capifony_puts_ok
        end
    end

    namespace :config do

        desc "Rename params in supervisor worker conf"
        task :rename, :roles => :app, :except => { :no_release => true } do
            puts "--> Rename params in worker config".yellow

            application_name = fetch(:worker_group)
            env = fetch(:symfony_env_prod)

            result = ERB.new(worker_template).result(binding)

            put result, worker_conf_file, :mode => 0644

        end

        desc "Create worker symlink in supervisor conf.d"
        task :create, :roles => :app, :except => { :no_release => true } do
            capifony_pretty_print "--> Symlink worker conf to supervisor conf.d"
            run "sudo ln -snf #{worker_conf_file} #{worker_symlink_file}"
            capifony_puts_ok
        end

        desc "Remove worker symlink from supervisor conf.d"
        task :remove, :roles => :app, :except => { :no_release => true } do
            print "--> Remove worker conf symlink ".yellow + worker_symlink_file.red + " from supervisor conf.d...".yellow
            if 'true' == capture("sudo sh -c 'if [ -h #{worker_symlink_file} ] ; then rm -f #{worker_symlink_file}; echo \"true\"; fi'").strip
                print '✔'.green << "\n"
            else
                print '✘'.red << "\n"
            end
        end
    end

end