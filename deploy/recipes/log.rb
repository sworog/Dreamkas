namespace :log do
    {
        :important => Logger::IMPORTANT,
        :info      => Logger::INFO,
        :debug     => Logger::DEBUG,
        :trace     => Logger::TRACE
    }.each do |action, level|
        desc "Set logger level to #{action.to_s}"
        task action do
            logger.level = level
        end
    end
end