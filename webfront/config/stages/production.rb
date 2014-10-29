server 'beta.dreamkas.ru', :app, :web, :primary => true
set :domain,      "beta.dreamkas.ru" unless exists?(:domain)
set :host,        "beta" unless exists?(:host)
set :branch,      "release-0.44" unless exists?(:branch)
set :application, "#{host}.#{app_end}"
set :application_url, "http://#{host}.dreamkas.ru"
set :api_url, "api.dreamkas.ru" unless exists?(:api_url)