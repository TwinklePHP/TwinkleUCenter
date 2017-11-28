<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twinkle User Center Sign In</title>
</head>
<body>

<div style="text-align: center; margin: 100px auto">
    
    <?php if (empty($step)): ?>
    
        <h3>用户登录演示</h3>
        
        <form action="/demo/sign-in" method="post">
            用户名：<input type="text" name="username">
            密码：<input type="password" name="password">
            <input type="submit" value="登录">
        </form>
    
    <?php elseif ($step == 'login_success'): ?>
    
        <h3>登录成功！</h3>
        <p>用户名字：<?= $info['first_name'] ?></p>
        <p>用户邮箱：<?= $info['email'] ?></p>
        <p>登录次数：<?= $info['login_count'] ?></p>
        <p>上次登录时间：<?= date('Y-m-d H:i:s', $info['last_login_time']) ?></p>
        
        <p><a href="/demo/index">返回</a> </p>

    <?php elseif ($step == 'login_fail'): ?>
        
        <h3>登录失败！</h3>
        <p>用户名或密码错误。</p>
        <p><a href="/demo/index">返回</a> </p>
    
    <?php endif; ?>

</div>

</body>
</html>