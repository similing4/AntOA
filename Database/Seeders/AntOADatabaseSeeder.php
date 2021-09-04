<?php

namespace Modules\AntOA\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AntOADatabaseSeeder extends Seeder {
    /**
     * 数据库Seeder，创建后台用户表antoa_user以供项目使用.
     *
     * @return void
     */
    public function run() {
        $pre = config("database.connections.mysql.prefix");
        Db::update("drop table if exists " . $pre . "antoa_user");
        Db::update("CREATE TABLE `" . $pre . "antoa_user` (
             `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
             `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
             `password` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
             `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（0正常1禁用）',
             `role` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户权限',
             `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
             PRIMARY KEY (`id`) USING BTREE
            ) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC
        ");
        Db::table("antoa_user")->insert([
            "username" => "admin",
            "password" => md5("admin"), //默认密码为admin，如果需要修改默认密码可以在这里修改
            "role"     => "[1]" //默认有超级管理员角色
        ]);
        Db::update("CREATE TABLE `antoa_role` (
             `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
             `name` varchar(200) NOT NULL COMMENT '角色名称',
             PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
        Db::table("antoa_role")->insert([
            "id"   => "1",
            "name" => "超级管理员"
        ]);
    }
}
