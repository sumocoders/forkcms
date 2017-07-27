server "php71-001.sumohosting.be", user: "$production-user", roles: %w{app db web}

set :document_root, "/home/$production-user/domains/$domain/public_html"
set :deploy_to, "/home/$production-user/apps/#{fetch :project}"

set :opcache_reset_strategy, "file"
set :opcache_reset_base_url, "#{fetch :production_url}"

### DO NOT EDIT BELOW ###
set :keep_releases, 3
set :php_bin, "php"

SSHKit.config.command_map[:composer] = "#{fetch :php_bin} #{shared_path.join("composer.phar")}"
SSHKit.config.command_map[:php] = fetch(:php_bin)
