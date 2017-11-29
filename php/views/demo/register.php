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

                <h3>用户注册演示</h3>

                <form action="/demo/sign-up" method="post">
                    <p>用户名：<input type="text" name="username"></p>
                    <p>密码：<input type="password" name="password"></p>
                    <p>确认密码：<input type="password" name="repassword"></p>
                    <p><input type="submit" value="注册"></p>
                </form>

            <?php elseif ($step == 'login_success'): ?>

                <form action="/demo/sign-in" method="post">
                    <h3>登录页面！</h3>
                    <p>用户名：<input type="text" name="username"></p>
                    <p>密码：<input type="password" name="password"></p>
                    <p><input type="submit" value="登录"></p>
                </form>

            <?php elseif ($step == 'login_fail'): ?>

                <h3>注册失败！</h3>
                <p><a href="/demo/register">返回注册</a> </p>

            <?php endif; ?>

        </div>

    </body>
</html>