namespace :webfront do

    task :default do
        create_config
        rename_api
        build
        symlink
    end

    desc "Build webfront app"
    task :build, :roles => :app, :except => { :no_release => true } do
        run "cd #{latest_release} && npm install && npm run build"
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
