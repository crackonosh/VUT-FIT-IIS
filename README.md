# IIS - Fituska with gamification
### Requirements
- `composer`
- `docker` with `docker-compose` (necessary if using [docker](#using-docker) for running the app)

### Installation
We can start the application 3 different ways using:
1. [docker](#using-docker)
2. [composer](#using-composer) 
3. [XAMP/MAMP](#using-xamp-mamp)
##### Using docker
All following commands should be run in root directory:
- run `docker-compose up` - this sets up php server, mysql & phpmyadmin (you might wanna run `docker-compose up -d` for _detached_ mode aka. running in background)
- run `./install-dependencies.sh`
- run `./update-schema.sh`

##### Using composer
For using this method you have to setup database on your own and change database settings in `fituska-api/settings.php` file. You'll also need to make sure you are using correct version of PHP (tested with 7.3)
- move to `fituska-api/` folder and run `composer install` and stay in this folder
- run `composer start` - this starts php server
- run `php vendor/bin/doctrine orm:schema-tool:update --force --dump-sql` - this creates tables by `fituska-api/src/Domain/` folder into database

###### Using XAMP MAMP
Almost same as using composer, but you'll set your "apache directory" to `fituska-api/src/public/` and then run the server
- then run `php vendor/bin/doctrine orm:schema-tool:update --force --dump-sql` in `fituska-api/` - this creates tables by `fituska-api/src/Domain/` folder into database

### Development
- TBD