# lumen-zipfile

#### 介绍
使用lumen框架，实现zip文件的上传，自动压缩，预览文件，zip文件的上传，项目的创建和删除 
（主要是管理产品图zip包，自动解压，在线预览）

#### 软件架构
软件架构说明

### 技术涉及
使用第三方包，操作文件，压缩等
1.  使用symfony/filesystem 操作文件及目录
2.  使用Finder包 统计文件个数，读取层级目录及文件等
3.  使用nelexa/zip包 替换php原生解压ZipArchi（可以兼容windows和linux及mac下压缩包的乱码及各种问题）


#### 安装教程

1.  git clone https://gitee.com/mikehub/lumen-zipfile.git或https://github.com/mikeside/lumen-zipfile.git
2.  进入项目，运行 composer install

#### 使用说明

1.  首先新建项目
2.  上传项目demo
3.  列表管理项目，可覆盖上传demo或删除项目

#### 参与贡献

1.  Fork 本仓库
2.  新建 Feat_xxx 分支
3.  提交代码
4.  新建 Pull Request


#### 特技

1.  使用 Readme\_XXX.md 来支持不同的语言，例如 Readme\_en.md, Readme\_zh.md
2.  Gitee 官方博客 [blog.gitee.com](https://blog.gitee.com)
3.  你可以 [https://gitee.com/explore](https://gitee.com/explore) 这个地址来了解 Gitee 上的优秀开源项目
4.  [GVP](https://gitee.com/gvp) 全称是 Gitee 最有价值开源项目，是综合评定出的优秀开源项目
5.  Gitee 官方提供的使用手册 [https://gitee.com/help](https://gitee.com/help)
6.  Gitee 封面人物是一档用来展示 Gitee 会员风采的栏目 [https://gitee.com/gitee-stars/](https://gitee.com/gitee-stars/)
