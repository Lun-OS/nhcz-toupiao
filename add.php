<?php
// QQ号码
$qq = '1596534228';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QQ加好友</title>
</head>
<body>
    <!-- 构建QQ加好友链接，并设置为<a>标签的href属性 -->
    <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $qq; ?>&site=qq&menu=yes" target="_blank">
        点击这里加我为好友
    </a>
</body>
</html>