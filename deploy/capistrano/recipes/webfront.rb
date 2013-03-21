namespace :webfront do

    task :default do
        build
        create_index
    end

    desc "Build webfront app"
    task :build, :roles => :app, :except => { :no_release => true } do
        run "cd #{latest_release} && bash lh_build.cmd"
    end

    desc "Copy index_%env%.xml"
    task :create_index, :roles => :app, :except => { :no_release => true } do
        run "cd #{latest_release} && cp index.#{stage}.xml index.xml"
    end

end