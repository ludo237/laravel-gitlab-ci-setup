# Laravel Gitlab Ci Setup

Complete setup for Laravel and Gitlab CI.

## How it works

This is a set of script plus a complete `gitlab-ci.yml` file that will create the right stages for Unit, Feature and Browser Tests as well as Autodeploy!

## How to contribute

Simply head to the [Repository on Gitlab](https://gitlab.com/ludo237/laravel-gitlab-ci-setup), open issues or clone the repository and create pull requests.

## How to use

First thing first you **must have** a Laravel-based project on Gitlab otherwise this won't work at all.

Once it's done simply copy the `scripts` folder into your root application folder, then copy `.gitlab-ci.yml.example` file into your root application folder and rename it to `.gitlab-ci.yml` in order to be visible by Gitlab Ci.

If you want to create an `almost` 0 downtime deployment you can also leverage `Envoy.blade.php` in your project folder. Otherwise disable the `deploy` stage inside `.gitlab-ci.yml` file

```TXT
Remember that Envoy is an automated script that will run in your production server, be sure of what you are doing!
```

Last thing, you need to go to your [Repository CI Settings](https://gitlab.com/path/to/project/settings/ci_cd) and create the necessary [variables](https://gitlab.com/help/ci/variables/README#variables) for the deploy script.

### Customize Gitlab CI Config

If you want to customize Gitlab CI you can change the DB service for example, the default is set to MariaDB 10.3 but you can easily change it with MySQL for instance.

### Autodeploy Variables

- GITLAB_SSH_PASS **SSH password of the production user used to access your server**
- PRODUCTION_COMMAND **A path to envoy command** example `/var/www/your-project/.composer/vendor/bin/envoy run deploy`
- PRODUCTION_DIR_PATH **Root application of your production env** example `/var/www/your-project`
- PRODUCTION_HOST **IP Address of your production server**
- PRODUCTION_PORT **Production SSH Port** specify this even if the port is the default 22
- PRODUCTION_USERNAME **Username for the SSH command**

### Envoy Variables

If you choose to stick with autodeploy heads up to `Envoy.blade.php` and fill the variables inside `@setup` block

- $repository is the git@gitlab address of your repository. Example: `git@gitlab.com:ludo237/laravel-gitlab-ci-setup.git`
- $user_directory if your server hosts multiple domains this can be useful otherwise just specify your root web folder. Example: `/var/www/`
- $project_directory the path of your root application folder. Example: `/var/www/your-project`
- $production_directory the path of the production directory used by the web server if any. If you don't have a specific project directory leave it empty. Example: `/var/www/your-project/public_html`
- $reports_directory the path where the Envoy log will be stored., default is in Laravel `/storage/logs` folder
- $releases_directory path used to clone and fetch latest diff from your repository. Example: `/var/www/your-project/releases`
- $report_file the name of the report file, default is `release_log.txt`
