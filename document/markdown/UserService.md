# TwinkleUCenter 服务文档 #

## 服务说明
**★ 接口地址**  

	/rpc/user	

##接口传送门

登录接口

注册接口

## 1、登录接口 ##

**★ 方法名称**  

    function login($email, $password)

**★ 参数说明**  

| 名称       | 类型     | 是否必须 | 默认值  | 描述   |
| -------- | ------ | ---- | ---- | :--- |
| email    | string | 是    | 无    | 邮箱   |
| password | string | 是    | 无    | 密码   |

## 2、注册接口

**★ 方法名称**  

    function register($data = [])
**★ 参数说明**

| 名称   | 类型       | 是否必须   | 默认值  | 描述   |      |
| ---- | -------- | ------ | ---- | ---- | ---- |
| data | array    | 是      | 无    |      |      |
|      | 名称       | 类型     | 是否必须 | 默认值  | 描述   |
|      | email    | string | 是    | 无    | 邮箱   |
|      | password | string | 是    | 无    | 密码   |
|      |          |        |      |      |      |

