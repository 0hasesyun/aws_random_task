<!DOCTYPE html>
<html lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="description" content="このページの説明文">
  <title>このページのタイトル</title>
</head>
<body>

<?php
  try {
    //DB名、ユーザー名、パスワード
	$pdo = new PDO(
		'mysql:dbname=random_task;host=hasesyun-database-1.cynnhbtjrsyi.ap-northeast-1.rds.amazonaws.com;charset=utf8mb4',
		'aws_user',
 		'password',
 		[
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
       		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
 		]
	);
	$stmt = $pdo->prepare('SELECT * FROM dice');
	$stmt->execute();
	$row_count = $stmt->rowCount();

	//input_post.phpの値を取得
    $flag = $_POST['task_flag'];
    $rate = $_POST['task_rate'];
    $content = $_POST['task_content'];

	$sql = "INSERT INTO task (flag, rate, content) VALUES (:flag, :rate, :content)";
	$stmt = $pdo->prepare($sql); 
    $params = array(':flag' => $flag, ':rate' => $rate, ':content' => $content);
    $stmt->execute($params); 

    echo "<p>flag: ".$flag."</p>";
    echo "<p>rate: ".$rate."</p>";
    echo "<p>content: ".$content."</p>";
    echo '<p>で登録しました。</p>'; // 登録完了のメッセージ
  } catch (PDOException $e) {
  exit('データベースに接続できませんでした。' . $e->getMessage());
  }

?>
</body>
</html>
