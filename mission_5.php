<?php
	//---データベース接続---//
	$dsn = 'mysql:dbname=tb*****db;host=localhost';
	$user = 'tb-*******';
	$password = '**********';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	//テーブル作成//
	$sql = "CREATE TABLE IF NOT EXISTS mission5"."("
	."id INT AUTO_INCREMENT PRIMARY KEY,"	//自動的に連番
	."name char(32),"
	."comment TEXT,"
	."time DATETIME"
	.");";
	$stmt = $pdo->query($sql);
?>

<?php
	$Edit_name = null;
	$Edit_comment = null;
	$edit = null;
	$PASS = "0000";
//フォームに表示する編集内容を取得//
	if (isset($_POST["edit"]) && isset($_POST["edit_pass"])){
	$edit = $_POST["edit"];
	$EditPass = $_POST["edit_pass"];
	if (strlen($edit)>0 && strlen($EditPass)>0){
	if ($EditPass == $PASS){
		$sql = "SELECT * FROM mission5 WHERE id ="." $edit";
		$stmt = $pdo -> query($sql);
		$results = $stmt ->fetchAll();
		foreach ($results as $row){
			$Edit_name = $row['name'];
			$Edit_comment = $row['comment'];
		}
	}else{echo "！！編集パスワードが違います！！"."<br>";}
	}
	}
?>

<html>
<head>
  <meta charset="utf-8">
  <title>mission_5掲示板</title>
</head>

<body>
<!--名前・コメントの入力フォーム-->
  <form action="" method="post">
<p>
	<input type="text" name="name" placeholder="名前" value="<?php echo $Edit_name; ?>"><br>
	<input type="text" name="comment" placeholder="コメント" value="<?php echo $Edit_comment; ?>"><br>
	<input type="hidden" name="edit_number" value="<?php echo $edit; ?>">
	<input type="text" name="new_pass" placeholder="パスワード(0000)" >
	<input type="submit" name="submit" value="送信"><br>
</p>
<!--削除番号の入力フォーム-->
<p>
	<input type="text" name="remove"  placeholder="削除対象番号"><br>
	<input type="text" name="remove_pass" placeholder="パスワード">
	<input type="submit" name="delete" value="削除"><br>
</p>
<!-編集番号の入力フォーム-->
<p>
	<input type="text" name="edit" placeholder="編集対象番号"><br>
	<input type="text" name="edit_pass" placeholder="パスワード">
	<input type="submit" value="編集"><br>
</p>
</form>

<?php
	$edit_number = null;
	$PASS = "0000";
	//編集//
	if (isset($_POST["edit_number"]) && isset($_POST["new_pass"])){
	$edit_number = $_POST["edit_number"];
	$UpdatePass = $_POST["new_pass"];
	if (strlen($edit_number)>0 && strlen($UpdatePass)>0){
	if ($UpdatePass == $PASS){
		$id = $edit_number;   //編集する投稿番号
		$name = $_POST["name"];      //編集後
		$comment = $_POST["comment"];   //編集後
		$time = date("Y/m/d H:i:s");
		$sql = 'update mission5 set name=:name, comment=:comment, time=:time where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
		$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt -> bindParam(':time', $time, PDO::PARAM_STR);
		$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
		$stmt -> execute();
	}else{echo "パスワードが違います。"."<br>";}
	}
	}
	//編集or新規投稿
	if ($edit_number == null){   //aaa
	//入力//
	if (isset($_POST["name"]) && isset($_POST["comment"]) && isset($_POST["new_pass"])){
	$name = $_POST["name"];
	$comment = $_POST["comment"];
	$time = date("Y/m/d H:i:s");
	$new_pass = $_POST["new_pass"];
	if (strlen($name)>0 && strlen($comment)>0 && strlen($new_pass)>0){
		if ($new_pass==$PASS){
			$sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, time) VALUES (:name, :comment, :time)");
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> bindParam(':time', $time, PDO::PARAM_STR);
			$sql -> execute();
		}else{echo "入力パスワードが違います。"."<br>";}
	}
	elseif (strlen($_POST["remove"])==0 && strlen($_POST["edit"])==0){
		if (strlen($name)==0 && strlen($comment)==0 && strlen($new_pass)==0){echo "名前、コメント、パスワードを記入してください。"."<br>";}   //全空白
		elseif (strlen($name)==0){echo "名前が未記入です。"."<br>";}
		elseif (strlen($comment)==0){echo "コメントが未記入です。"."<br>";}
		elseif (strlen($new_pass)==0){echo "パスワードを記入してください。"."<br>";}
	}
	}
	//削除//
	if (isset($_POST["remove"]) && isset($_POST["remove_pass"])){
	$remove = $_POST["remove"];
	$RemovePass = $_POST["remove_pass"];
	if (strlen($remove)>0 && strlen($RemovePass)>0){
	if ($RemovePass == $PASS){
	$id = $remove;   //削除する投稿番号
	$sql = 'delete from mission5 where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	}else{echo"削除パスワードが違います。"."<br>";}
	}
	}
	}   //aaa
?>

<?php
	echo "---投稿一覧---"."<br>";
	$sql = 'SELECT * FROM mission5';
	$stmt = $pdo -> query($sql);
	$results = $stmt ->fetchAll();
	foreach ($results as $row){
		echo $row['id'].' ';
		echo $row['name'].' ';
		echo $row['comment'].' ';
		echo $row['time'].'<br>';
	}
?>
</body>
</html>
