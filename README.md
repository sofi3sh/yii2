# Metalpark

CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=metalpark',
    'username' => 'metalpark',
    'password' => 'metalpark',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.

INSTALLATION
-------------

### Install with Docker

Update your vendor packages

    docker-compose run --rm php composer update --prefer-dist

Run the installation triggers (creating cookie validation code)

    docker-compose run --rm php composer install

Start the container

    docker-compose up -d

You can then access the application through the following URL:

    http://127.0.0.1:8000

If you see an error about write permissions to `web/assets/` or `runtime/` it's because
the local file owner id is different from `1000` which is the `www-data` user in the container.
To fix this, try:

```
docker exec -it metalpark_php_1 chown www-data web/assets runtime
```

MIGRATIONS
-------------

### Running migrations with Docker
- Run yii-rbac migrations:
```
docker-compose run -rm php yii.php migrate --migrationPath=@yii/rbac/migrations
```

- Run yii-i18n migrations:
```
docker-compose run -rm php yii.php migrate --migrationPath=@yii/i18n/migrations
```
- Run application's migratins:
```
docker-compose run -rm php yii.php migrate
```

Further reading:
- [Role Based Access Control (RBAC)](https://www.yiiframework.com/doc/guide/2.0/en/security-authorization#rbac)
- [Internationalization](https://www.yiiframework.com/doc/guide/2.0/en/tutorial-i18n)

DEVELOPMENT
------------
### Run with Docker

```
docker-compose start
```

```
docker-compose stop
```

### Build css files

```
cd web/themes/limitless/
npm install
gulp
```
### Resetting browser cache for js and css
Every time css or js files are compiled by Gulp, the parameter `externalFileVersion` in the `config/params.php` file should increase by 1

## ReactJS frontend
We also use ReactJS for building complex interfaces, see all the details [here](frontend/README.md).

DEPLOYMENT
------------
We deploy changes to the production environment using `Gitlab Runner` and `shell executor`.

Every time new changes is pushed to a branch the Gitlab Runner runs `frontend tests` on the branch.

If the tests passed a merge request with the changes can be merged into the master branch.

After merging the merge request the developer have to run the production stage of the pipeline in order to deliver the changes to the server.

The Gitlab Runner runs the `deploy.sh` script each time it pick ups new changes
