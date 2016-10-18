#git reset --hard
#git pull origin master
composer install --no-interaction --prefer-dist
php artisan migrate --force
./node_modules/.bin/bower install
npm install
gulp lets_cook
gulp admin