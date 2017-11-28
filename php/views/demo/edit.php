<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php if ('read' == $step): ?>
    <form action="/demo/edit" method="post">
        <input type="hidden" value="<?= $user_info['user_id']; ?>" name="user_id">
        <input type="text" value="<?= $user_info['first_name']; ?>" name="first_name">
        <input type="text" value="<?= $user_info['last_name']; ?>" name="last_name">
        <input type="submit" value="保存">
    </form>
    <div>
        <p>当前用户信息</p>
        <p>
        <pre>
            <?= var_export($user_info, true); ?>
        </pre>
        </p>
    </div>

<?php else: ?>
    <?= $msg; ?>
<? endif; ?>
</body>
</html>