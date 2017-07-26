set :branch, "master"
server "php71-001.sumohosting.be", user: "xxx", roles: %w{app db web}
set :document_root, "/home/xxx/public_html"
set :deploy_to, "/home/xxx/apps/#{fetch :project}"
set :fcgi_connection_string, "/usr/local/php71/sockets/xxx.sock"

### DO NOT EDIT BELOW ###
set :keep_releases, 3
set :php_bin, "php"

SSHKit.config.command_map[:composer] = "#{fetch :php_bin} #{shared_path.join("composer.phar")}"
SSHKit.config.command_map[:php] = fetch(:php_bin)
