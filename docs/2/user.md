AntOA的后台用户受数据库中antoa_user表管理，antoa_role表起到辅助的作用。

## 用户表介绍

antoa_user表结构如下：
```
CREATE TABLE `antoa_user` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
 `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
 `password` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
 `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（0正常1禁用）',
 `role` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户权限',
 `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
 PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC
```
其中用户名、密码两项用于登录验证，密码加了一层md5，状态为1则不能登录。用户权限为一个json数组的字符串，其每一项代表这个用户的权限。默认Seeder会创建一个账号密码均为admin的用户。
antoa_role表结构如下：
```
CREATE TABLE `antoa_role` (
 `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
 `name` varchar(200) NOT NULL COMMENT '角色名称',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8
```
其id为antoa_user中role字段的每一项的值。

## 授权与鉴权过程
用户后台登录页定义于AntOA/frontend/src/pages/login/Login.vue中：

1.首先登录页会调用鉴权接口：/api/antoa/auth/auth

2.如果登录鉴权判断你已登录那么直接跳转第5步。

3.输入账号密码登录会调用登录接口：/api/antoa/auth/login

4.登录成功后Login.vue会将登录的Token写入localStorage.AuthToken中供接口调用使用。

5.通过获取配置接口获取配置：/api/antoa/auth/config

6.将获取到的配置commit到Vuex中用于菜单渲染：
```
this.$store.commit('setting/setMenuData', e.routes);
this.$store.commit('setting/setCustomTitleList', e.title_map);
```
7.跳转到后台首页（Home页）