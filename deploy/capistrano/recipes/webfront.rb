namespace :webfront do

    task :default do
        build
        create_index
        rename_api
    end

    desc "Build webfront app"
    task :build, :roles => :app, :except => { :no_release => true } do
        run "cd #{latest_release} && sh lh_build.cmd"
    end

    desc "Create index.xml"
    task :create_index, :roles => :app, :except => { :no_release => true } do
        run "cd #{latest_release} && cp index.xml.template index.xml"
    end

    task :rename_api, :roles => :app, :except => { :no_release => true } do
        set :api_url, "#{host}.#{stage}.api.lighthouse.cs" unless exists?(:api_url)
        puts "--> API url name in ".yellow + "index.xml".bold.yellow + " will be set to ".yellow + "#{api_url}".red
        run "sed -i 's/%api_url%/#{api_url.gsub('/', '\\/')}/g' #{latest_release}/index.xml"
    end
end