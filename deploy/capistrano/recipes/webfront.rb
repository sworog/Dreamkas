namespace :webfront do

    task :default do
        create_config
        rename_api
        build
        symlink
    end

    desc "Build webfront app"
    task :build, :roles => :app, :except => { :no_release => true } do
        flag = fetch(:npm_flag, '')
        puts "--> NPM build".yellow
        run "cd #{latest_release} && npm install #{flag} && npm run build"
    end

    desc "Create config.js"
    task :create_config, :roles => :app, :except => { :no_release => true } do
        run "cd #{latest_release}/src && cp config.js.template config.js"
    end

    desc "Symlink web folder"
    task :symlink, :roles => :app, :except => { :no_release => true } do
        run "cd #{latest_release} && ln -snf build web"
    end

    task :rename_api, :roles => :app, :except => { :no_release => true } do
        set :api_url, "#{host}.#{stage}.api.lighthouse.pro" unless exists?(:api_url)
        puts "--> API url name in ".yellow + "config.js".bold.yellow + " will be set to ".yellow + "#{api_url}".red
        run "sed -i 's/%api_url%/#{api_url.gsub('/', '\\/')}/g' #{latest_release}/src/config.js"
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
