require 'capistrano/recipes/deploy/strategy/remote_cache'

class Capistrano::Deploy::Strategy::RemoteCacheSubfolder < Capistrano::Deploy::Strategy::RemoteCache

    private

    def copy_repository_cache
        logger.trace "copying the cached version to #{configuration[:release_path]}"
        if copy_exclude.empty?
            run "cp -RPp #{repository_cache_subdir} #{configuration[:release_path]} && #{mark}"
        else
            exclusions = copy_exclude.map { |e| "--exclude=\"#{e}\"" }.join(' ')
            run "rsync -lrpt #{exclusions} #{repository_cache_subdir}/ #{configuration[:release_path]} && #{mark}"
        end
    end

    def repository_cache_subdir
        if configuration[:deploy_subdir]
            File.join(repository_cache, configuration[:deploy_subdir])
        else
            repository_cache
        end
    end
end