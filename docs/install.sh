git clone https://gitee.com/similing/AntOA.git
mv AntOA/docs/antoa.tar.gz .
rm AntOA -rf
tar -xvf antoa.tar.gz -C .
rm antoa.tar.gz -f
rm composer.lock -f
sudo -u www composer install
php artisan key:generate -q
echo "安装完成接下来您需要："
echo "配置/config/antoa.php的路由信息"
echo "配置/.env文件的数据库配置信息"
echo "设置网站运行目录为public"
echo "不要禁用php的proc_open函数"
echo "执行以下命令以初始化AntOA相关数据库："
echo "sudo -u www php artisan module:seed AntOA"
