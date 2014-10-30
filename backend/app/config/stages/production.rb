server 'api.dreamkas.ru', :app, :web, :primary => true
set :domain,      "api.dreamkas.ru" unless exists?(:domain)
set :host,        "beta" unless exists?(:host)
set :branch,      "primary" unless exists?(:branch)
set :application, "#{host}.#{app_end}"
set :application_url, "http://api.dreamkas.ru"