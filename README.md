Microwall
=========

The open source high-performance blog management system

这是一个开源的、高性能的博客管理系统，使用Yii Framework框架写成。但是请注意，这不是一个普通安装软件，你必须至少懂得一些Web开发知识才能顺利安装。

作者网站：http://microwall.cn

安装步骤：
=========
    1. 新建数据库，导入/protected/data/data.mysql.sql
    2. 进入/protected/config/main.php和/protected/config/main.tpl修改数据库、用户名及密码
    3. 打开后台，如：index.php/admin或index.php?r=admin，Microwall默认用户名：admin，密码：microwall

功能细节：
=========
    1. Microwall默认有4个小时的缓存时间，所以发布文章后可能不会立即在前台显示，需要在设置中刷新缓存
    2. 发布文章限制了很多条件，如：必须上传图片，这是因为默认的主题是必须显示图片的，您可以在开发新的主题中进行修改
    3. 当仅仅存入草稿时，只有标题与分类是必须的
    4. 添加用户时有角色选择，权限大小：管理员 > 网站编辑 > 作者，用户一旦添加，角色不可修改
    5. 添加用户有一个头像选择，这是可选的