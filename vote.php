<?php
session_start();

// 检查是否已经投票5次
if (isset($_SESSION['votes_today']) && $_SESSION['votes_today'] >= 5) {
    echo '今天已经投满5票了！';
    exit;
}

$id = $_GET['id'];
$data = file('data.ini', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$newData = array();
foreach ($data as $line) {
    $line = trim($line, '[]');
    $candidate = array();
    $parts = explode(',', $line);
    foreach ($parts as $part) {
        list($key, $value) = explode('=', $part);
        $key = trim($key, '"');
        $value = trim($value, '"');
        $candidate[$key] = $value;
    }
    if ($candidate['id'] == $id) {
        $candidate['votes'] = (int)$candidate['votes'] + 1;
    }
    $newLine = '[id="' . $candidate['id'] . '",name="' . $candidate['name'] . '",class="' . $candidate['class'] . '",votes="' . $candidate['votes'] . '",img="' . $candidate['img'] . '"]';
    $newData[] = $newLine;
}
file_put_contents('data.ini', implode("\n", $newData));

// 更新会话变量
$_SESSION['votes_today']++;

echo '投票成功！';
?>