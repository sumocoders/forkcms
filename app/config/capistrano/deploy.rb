set :client,  "sumo"
set :project, "forkcms"
set :repo_url, "https://github.com/sumocoders/forkcms.git"
set :production_url, "http://fork.verkoyen.eu"

### DO NOT EDIT BELOW ###
set :application, "#{fetch :project}"

set :deploytag_utc, false
set :deploytag_time_format, "%Y%m%d-%H%M%S"

set :files_dir, %w(src/Frontend/Files)
