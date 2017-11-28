# TwinkleUCenter
用户中心服务，基于RPC开发。抽取通用的业务逻辑封装统一的接口服务，解决目前项目中每个端分别部署服务带来的劳动力浪费问题。  
项目使用composer管理依赖，使用到了php扩展yar。如未安装这两个东西，请先安装。

## 初始化
1.下载该项目

``` 
git clone https://github.com/TwinklePHP/TwinkleUCenter.git
```
2.composer安装依赖

```
cd ~/php
composer intall
```

3.导入sql

```
配置好数据库连接等信息（可拷贝~/php/config/web.dev.php文件）
导入数据表文件 ~/document/sql/db_twinkle_ucenter.sql
```