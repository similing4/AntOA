<?php

return [
    'name'        => 'AntOA',
    'mode'        => ['vue', 'html'], //页面模式，可选项为vue与html，默认自带后台接口，如果设置vue则开启Ant Design Vue Admin页面开发，如果设置html则开启普通blade页面开发。无论该参数是否设置，开发的接口都会被开放。
    //菜单路由配置，uri为完整URL链接，title为在菜单上显示的内容，breadcrumbTitle为面包屑上显示的标题，不设置默认为title
    'menu_routes' => [
        //前端页面的路由，html模式下为页面绝对地址，vue模式下为路由path地址
        [
            "title"    => "首页", //页面名称，将显示在侧边栏及标签页上，如果不设置breadcrumbTitle也将设置为面包屑。
            "isHome"   => true, //使用ant-design-vue-admin时此指定此页面组为首页/首页，设置的children将被无视，如需设置首页内容需要自定义vue项目的home.vue文件，只能设置在第一层且只能设置一个。
            "children" => [
                //二级导航栏
                [
                    "uri"   => "/admin/home/home",
                    "title" => "首页"
                ]
            ]
        ], [
            "title"      => "用户管理",
            "role_limit" => [1], //权限限制，不设置为不限制
            "children"   => [
                [
                    "uri"             => "/admin/user/list",
                    "title"           => "用户管理",
                    "breadcrumbTitle" => "用户管理列表页",
                    "role_limit"      => [1]
                ],
                [
                    "visible"    => false, //设置左侧导航栏中不显示该页面
                    "uri"        => "/admin/user/create",
                    "title"      => "用户创建页",
                    "role_limit" => [1]
                ],
                [
                    "visible"    => false,
                    "uri"        => "/admin/user/edit",
                    "title"      => "用户编辑页",
                    "role_limit" => [1]
                ]
            ]
        ]
    ],
    //七牛云的配置，如果你使用了七牛云文件上传那么该项必填
    'config'      => [
        'qiniu' => [
            'access_key' => '',  //七牛云的AccessKey，可以在用户头像下的秘钥管理处获取
            'secret_key' => '',  //七牛云的SecretKey，同上
            'bucket'     => '',  //Bucket名称，七牛云的对象存储-空间管理中的空间名称即为Bucket名称
            'url'        => '' //访问域名，如https://qn.github.com/，注意要带末尾的斜杠。可以在七牛云的对象存储-空间管理中的域名中绑定，http或https由空间配置决定，请尽量不要用测试域名避免因过期而造成困扰。
        ]
    ]
];
