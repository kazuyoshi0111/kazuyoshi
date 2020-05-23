<!DOCTYPE html>
<html lng="ja">
<head>
	<meta charset="utf-8">
<title>掲示板</title>
</head>
<body>

<h1>ログイン認証</h1><br>
<style>
h1 {
  /*線の種類（実線） 太さ 色*/
  border-bottom: solid 2px black;
}
</style>

	<?php
	//データベースに接続
	$dsn = 'mysql:dbname=tb210328db;host=localhost;host=localhost';
	$user = 'tb-210328';
	$password = 'S9FEK7z3tP';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	//  ログイン情報を管理するテーブルを作成(なければ)
	$sql = "CREATE TABLE IF NOT EXISTS login_table"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"		//番号はINT
	. "name char(32),"													//名前はChar
	. "login_id char(64),"												//IDはChar
	. "password char(32)"												//パスワードはChar
	.");";
	$stmt = $pdo->query($sql);	
	?>
	
	<?php
	// 入力されたID、パスワードを取得
		$get_id=$_POST['login_id'];
		$get_pass=$_POST['login_pass'];
	
	// 登録されたID、パスワードと一致しているかを確認
	$sql = 'SELECT * FROM login_table';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	$flag=0;
	foreach ($results as $row){
		if($row['login_id']===$get_id){
			if($row['password']===$get_pass){	//ID、パス一致の場合
				$flag=1;
				echo "ログイン完了".'<br>';
				echo "ようこそ ".$row['name']." さん".'<br><br>';
				$user_name=$row['name'];
				echo '<form method="post" action="mission_6_main.php"><input type="submit" value="次へ" name="next">';
				//echo '<input type="text" name="hidden_name" value=$user_name>';
			}
			else{			//パスワードが違う場合
				$flag=1;
				echo "IDまたはパスワードが違います";
				echo '<form method="post" action="mission_6_login.php"><input type="button" onclick="history.back()" value="戻る"></form>';
			}
			break;
		}
	}
	if($flag===0){		//指定されたIDが登録されていない場合
		echo "IDまたはパスワードが違います";
		echo '<form method="post" action="mission_6_login.php"><input type="button" onclick="history.back()" value="戻る"></form>';
	}
	
	?>
	
	<form method="post" action="mission_6_main.php">
	<input type="hidden" name="hidden_name" value="<?php if(isset($user_name))echo $user_name;?>">


</body>
</html>
