set :domain,      "beta.dreamkas.ru" unless exists?(:domain)
set :host,        "beta" unless exists?(:host)
set :branch,      "primary" unless exists?(:branch)

set (:application_folder) {"#{host}.#{app_end}"}
set (:application_url) {"http://#{host}.dreamkas.ru"}