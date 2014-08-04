
node.teamcity.agents.each do |name, agent| # multiple agents

  next if agent.nil? # support removing of agents

  agent = Teamcity::Agent.new name, node
  agent.set_defaults

  directory agent.home do
    action :create
    owner agent.user
    group agent.group
  end

  directory "#{agent.home}/.ssh" do
    action :create
    owner agent.user
    group agent.group
  end

  cookbook_file 'ssh_rsa_privite_key' do
    path "#{agent.home}/.ssh/id_rsa"
    owner agent.user
    group agent.group
    mode '0600'
  end

  cookbook_file 'ssh_rsa_public_key' do
    path "#{agent.home}/.ssh/id_rsa.pub"
    owner agent.user
    group agent.group
  end

end