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
    $result = mt_rand(1,100);
    $remaining = 1;
	
	$stmt;
	if($result > 50){
		$sql = "select id from task where flag = 0 order by rand() limit 1";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();	
	}
	else{
		$sql = "select id from task where flag = 1 order by rand() limit 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
	}
	$row=$stmt->fetch();
//	print_r($row);
	echo $row["id"];

	$sql = "INSERT INTO dice (result, task_id) VALUES (:result, :task_id)";
	$stmt = $pdo->prepare($sql); 
	$params = array(':result' => $result, ':task_id' => $row["id"]);
	$stmt->execute($params); 

//	echo "<p>count: ".$count."</p>";
    echo "<p>result: ".$result."</p>";
    echo "<p>remaining: ".$remaining."</p>";
    echo '<p>で登録しました。</p>'; // 登録完了のメッセージ


  } catch (PDOException $e) {
  exit('データベースに接続できませんでした。' . $e->getMessage());
  }

?>
</body>
</html>
