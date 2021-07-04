<?php

try {

    /* リクエストから得たスーパーグローバル変数をチェックするなどの処理 */

    // データベースに接続
    $pdo = new PDO(
        'mysql:dbname=random_task;host=hasesyun-database-1.cynnhbtjrsyi.ap-northeast-1.rds.amazonaws.com;charset=utf8mb4',
        'aws_user',
        'password',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
	$stmt = $pdo->prepare('SELECT * FROM task');
    $stmt->execute();

	while(true)
    {
      	$task_row=$stmt->fetch(PDO::FETCH_ASSOC);
      	if($task_row==false)
		{
 			break;
    	}
		$task_rows[]=$task_row;
 	}

	$stmt = $pdo->prepare('select task_id, count(task_id) as count, flag, content from dice inner join task on task_id = id group by task_id;');
    $stmt->execute();

	while(true)
    {
        $judge_row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($judge_row==false)
        {
            break;
        }
        $judge_rows[]=$judge_row;
    }
	
    /* データベースから値を取ってきたり， データを挿入したりする処理 */

} catch (PDOException $e) {

    // エラーが発生した場合は「500 Internal Server Error」でテキストとして表示して終了する
    // - もし手抜きしたくない場合は普通にHTMLの表示を継続する
    // - ここではエラー内容を表示しているが， 実際の商用環境ではログファイルに記録して， Webブラウザには出さないほうが望ましい
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage()); 

}

// Webブラウザにこれから表示するものがUTF-8で書かれたHTMLであることを伝える
// (これか <meta charset="utf-8"> の最低限どちらか1つがあればいい． 両方あっても良い．)
header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Example</title>
    </head>
    <body>
	
	<div id="judge_button" style="margin-left:10%;margin-right:10%;">
        <div><input type="submit" name="judge" value="judge" style="width:100%;font-size:30px;" class="button"></div>
    </div>
	<script>
      	function butotnClick(){
    		let name = prompt('タスクは終了しましたか？');
    		console.log(name);
			window.location.href = 'test.php';
		}

   		let button = document.getElementById('judge_button');
    	button.addEventListener('click', butotnClick);
   	</script>

	<!--レコード件数：<?php echo $row_count; ?>-->
    <table border='1'>
    <tr>judge_result
    <th>task_id</th>
    <th>count</th>
    <th>flag</th>
    <th>content</th>
    </tr>

    <?php
    foreach($judge_rows as $judge_row){
    ?>
    <tr>
      <td><?php echo $judge_row['task_id']; ?></td>
      <td><?php echo $judge_row['count']; ?></td>
      <td><?php echo $judge_row['flag']; ?></td>
      <td><?php echo $judge_row['content']; ?></td>
    </tr>
    <?php
    }
    ?>
	</table>
	
	<table border='2'>
	<tr>set_task
	<td>
	<div id="set_task_page">
		<form method="post" action="set_task.php">
		<div>flag <input type="radio" name="task_flag" value=1>Reward</div>
		<div>flag <input type="radio" name="task_flag" value=0 checked>Task</div>
    	<div>rate <input type="text" name="task_rate" size="30"></div>
    	<div>content <input type="text" name="task_content" cols="30" rows="10"></div>
		<div><input type="submit" name="set_task" value="set_task" class="button"></div>
		</form>
	</div>
	</td>
	</tr>
	<table border='2'>
	<tr>delete_task
	<td>
	<div id="delete_page">
        <form method="post" action="delete_task.php">
		<div>delete_id <input type="text" name="task_id"></div>
        <div><input type="submit" name="delete" value="delete" class="button"></div>
        </form>
    </div>
	</td>
	</tr>
	</table>

	<!--レコード件数：<?php echo $row_count; ?>-->
    <table border='1'>
    <tr>task_list
    <th>id</th>
    <th>flag</th>
    <th>rate</th>
    <th>content</th>
    </tr>

    <?php
    foreach($task_rows as $task_row){
    ?>
    <tr>
      <td><?php echo $task_row['id']; ?></td>
      <td><?php echo $task_row['flag']; ?></td>
      <td><?php echo $task_row['rate']; ?></td>
      <td><?php echo $task_row['content']; ?></td>
    </tr>
    <?php
    }
    ?>
    </table>

        <!-- ここではHTMLを書く以外のことは一切しない -->
    </body>
</html>
