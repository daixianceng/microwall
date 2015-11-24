Microwall
=========

The open source high-performance blog management system

这是一个开源的、高性能的博客管理系统，使用Yii Framework框架写成。但是请注意，这不是一个普通安装软件，你必须至少懂得一些Web开发知识才能顺利安装。

安装步骤：
-------

    1. 新建数据库，导入/protected/data/data.mysql.sql
    2. 进入/protected/config/main.php和/protected/config/main.tpl修改数据库、用户名及密码
    3. 打开后台，如：index.php/admin或index.php?r=admin，Microwall默认用户名：admin，密码：microwall

功能细节：
-------

    1. 发布文章限制了很多条件，如：必须上传图片，这是因为默认的主题是必须显示图片的，您可以在开发新的
       主题中进行修改
    2. 当仅仅存入草稿时，只有标题与分类是必须的
    3. 添加用户时有角色选择，权限大小：管理员 > 网站编辑 > 作者，用户一旦添加，角色不可修改
    4. 文章分类与页面是可拖动排序的
    5. 当进行删除分类等危险操作时，Microwall会检测并且会对用户有一个反馈
    
问题：
-------

    1. 在进行分类或页面排序时，可能会出现操作失灵问题，目前并未解决，原因并未发现任何错误
    2. 请确保安装有gd2
