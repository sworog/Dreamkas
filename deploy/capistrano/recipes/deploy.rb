namespace :deploy do

    desc "Check & setup environment if needed"
    task :setup_if_needed, :roles => :app, :except => { :no_release => true } do
        begin
            check
        rescue Exception => error
            setup
        end
    end

    task :restart do
        reload_php
    end

    desc "Reload PHP-FPM"
    task :reload_php, :roles => :app, :except => { :no_release => true } do
        run "#{sudo} service php5-fpm reload"
        capifony_pretty_print "--> Reloading PHP-FPM"
        capifony_puts_ok
    end

end