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

	//input_post.phpの値を取得
    $id = $_POST['task_id'];

	$sql = "DELETE FROM task WHERE id = (:id);
		alter table task auto_increment=1;";
	$stmt = $pdo->prepare($sql); 
    $params = array(':id' => $id);
    $stmt->execute($params); 

    echo "<p>id: ".$id."</p>";
    echo '<p>削除しました。</p>'; // 登録完了のメッセージ
  } catch (PDOException $e) {
  exit('データベースに接続できませんでした。' . $e->getMessage());
  }

?>
</body>
</html>
