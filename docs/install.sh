export COMPOSER_ALLOW_SUPERUSER=1
composer create-project --prefer-dist laravel/laravel blog "6.*"
mv blog/* .
rm blog -rf
composer require nwidart/laravel-modules 7.3
composer require wikimedia/composer-merge-plugin
php artisan module:make AntOA
rm composer.json -f
wget http://similing.gitee.io/antoa/composer.json
composer dump-autoload
cd Modules
rm AntOA -rf
git clone https://gitee.com/similing/AntOA.git
rm AntOA/.git -rf
cd AntOA/frontend
yarn
yarn build
cd ../../..
chmod -R 777 storage
chmod -R 777 bootstrap
wget http://similing.gitee.io/antoa/.env
chmod 777 .env
cp Modules/AntOA/Config/config.php.example ./config/antoa.php
echo "安装完成，请配置/config/antoa.php的路由信息与/.env文件的数据库配置信息"