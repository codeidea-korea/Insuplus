<?php
$link = mysqli_connect('dev-db-80.cdaumq0ugull.ap-northeast-2.rds.amazonaws.com', 'insplus', '!insplus#', 'insplus', 3306);
if (!$link) {
}
echo '연결 성공';

phpinfo();
?>
