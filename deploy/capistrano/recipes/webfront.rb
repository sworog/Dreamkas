namespace :webfront do

    task :default do
        build
        create_index
        rename_api
    end

    desc "Build webfront app"
    task :build, :roles => :app, :except => { :no_release => true } do
        run "cd #{latest_release} && bash lh_build.cmd"
    end

    desc "Create index.xml"
    task :create_index, :roles => :app, :except => { :no_release => true } do
        run "cd #{latest_release} && cp index.xml.template index.xml"
    end

    task :rename_api, :roles => :app, :except => { :no_release => true } do
        set :api_url, application_url.gsub(/^http:\/\//, '') unless exists?(:api_url)
        puts "--> API url name in ".yellow + "index.xml".bold.yellow + " will be set to ".yellow + "#{database_name}".red
        run "cd #{latest_release} && sed 's/%api_url%/#{api_url.gsub('/', '\\/')}/g' index.xml"
    end
end