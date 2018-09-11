@servers(["localhost" => "127.0.0.1"])

@setup
    $repository = "";
    $user_directory = "";
    $project_directory = $user_directory . "/" . "";
    $production_directory = $project_directory . "/" . "public_html";
    $reports_directory = $production_directory . "/". "storage/logs";
    $releases_directory = $project_directory . "/" . "releases";
    $release_date = date("YmdHis");
    $new_release_directory = $releases_directory ."/". $release_date;
    $report_file = $reports_directory . "/" . "release_log.txt";
@endsetup

@story("deploy")
    fetch_latest_release
    application_down
    copy_content
    npm_install
    npm_run
    composer_update
    artisan
    application_up
    clean_up
@endstory

@task("fetch_latest_release")
    echo "Fetching latest master tag..."
    [ -d {{ $releases_directory }} ] || mkdir -p {{ $releases_directory }}
    mkdir -p {{ $new_release_directory }}
    git clone --depth 1 {{ $repository }} {{ $new_release_directory }}
@endtask

@task("application_down")
    echo "Put the application to sleep..."
    cd {{ $production_directory }}
    php artisan down 2>&1
@endtask

@task("copy_content")
    echo "Copy content from {{ $new_release_directory }} to {{ $production_directory }}..."
    cd {{ $new_release_directory }}
    cp -R -f -v app config database nova public resources routes server.php composer.json package.json webpack.mix.js artisan {{ $production_directory }} 2>&1
@endtask

@task("npm_install")
    echo "Installing npm dependencies this might take a while..."
    cd {{ $production_directory }}
    npm update --silent >>{{ $report_file }} 2>&1
@endtask

@task("npm_run")
    echo "Running npm script this might take a while..."
    cd {{ $production_directory }}
    npm run production >>{{ $report_file }} 2>&1
@endtask

@task("composer_update")
    echo "Updating composer dependencies without dev flag..."
    cd {{ $production_directory }}
    composer update --no-dev --optimize-autoloader --no-interaction >{{ $report_file }} 2>&1;
@endtask

@task("artisan")
    echo "Executing artisan scripts..."
    cd {{ $production_directory }}
    php artisan cache:clear >>{{ $report_file }} 2>&1
    php artisan route:cache >>{{ $report_file }} 2>&1
    php artisan config:cache >>{{ $report_file }} 2>&1
    php artisan view:cache >>{{ $report_file }} 2>&1
    php artisan storage:link >>{{ $report_file }} 2>&1
@endtask

@task("application_up")
    echo "Awake application..."
    cd {{ $production_directory }}
    php artisan up
@endtask

@task("clean_up")
    echo "Clean up the mess..."
    rm -rf {{ $new_release_directory }}
    cd {{ $production_directory }}
    rm -rf node_modules
    echo "Application now live. Reports at " {{ $report_file }}
@endtask