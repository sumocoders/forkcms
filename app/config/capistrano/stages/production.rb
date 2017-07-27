server "php71-001.sumohosting.be", user: "tijs", roles: %w{app db web}

set :document_root, "/home/tijs/domains/fork.verkoyen.eu/public_html"
set :deploy_to, "/home/tijs/apps/#{fetch :project}"

set :opcache_reset_strategy, "file"
set :opcache_reset_base_url, "#{fetch :production_url}"

### DO NOT EDIT BELOW ###
set :branch, "5.0.0-dev"
set :keep_releases, 3
set :php_bin, "php"

SSHKit.config.command_map[:composer] = "#{fetch :php_bin} #{shared_path.join("composer.phar")}"
SSHKit.config.command_map[:php] = fetch(:php_bin)
