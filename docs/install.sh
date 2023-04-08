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
php artisan key:generate -q
echo "安装完成接下来您需要："
echo "配置/config/antoa.php的路由信息"
echo "配置/.env文件的数据库配置信息"
echo "设置网站运行目录为public"
echo "不要禁用php的proc_open函数"