<!DOCTYPE html>
<html lng="ja">
<head>
	<meta charset="utf-8">
<title>掲示板</title>
</head>

<body>

	<?php				
	//データベースに接続
	$dsn = 'mysql:dbname=tb******db;host=localhost;host=localhost';
	$user = 'tb-*******';
	$password = '*********';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	//  ログイン情報を管理するテーブルを作成(なければ)
	$sql = "CREATE TABLE IF NOT EXISTS login_table"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"		//番号はINT
	. "name char(32),"				//名前はChar
	. "login_id char(64),"				//IDはChar
	. "password char(32)"				//パスワードはChar
	.");";
	$stmt = $pdo->query($sql);	
	?>
	
	<h1>新規登録フォーム</h1>


<style>
h1 {
  /*線の種類（実線） 太さ 色*/
  border-bottom: solid 2px black;
}
	body {
	  margin:0;
	}
	form{
	  padding: 16px;
	}
	input {
	  box-sizing:border-box;
	}
	input[type="text"]{
	  margin: 0.5em 0;
	  padding: 0.5em;
	  width: 100%;
	  font-size:16px;
	  color: #999;
	  border-radius: 6px;
	}
	input[type="text"]:focus {
	  background-color: #e2ecf6;
	}
	input[type="password"]{
	  margin: 0.5em 0;
	  padding: 0.5em;
	  width: 100%;
	  font-size:16px;
	  color: #999;
	  border-radius: 6px;
	}
	input[type="password"]:focus {
	  background-color: #e2ecf6;
	}
	input[type="email"]{
	  margin: 0.5em 0;
	  padding: 0.5em;
	  width: 100%;
	  font-size:16px;
	  color: #999;
	  border-radius: 6px;
	}
	input[type="email"]:focus {
	  background-color: #e2ecf6;
	}
	input[type="submit"]{
	 border:1px solid #0086f9;
	 border-radius: 6px;
	 padding: 12px 48px;
	 font-size: 16px;
	 background: linear-gradient(0deg, #0086f9, #b6d6f7);
	color: #fff;
	font-weight: bold;
	}
	input[type="submit"]:hover{
	 beckground: linear-gradient(0deg, #2894f9,#d2e4f7);
	}
	input[type="submit"]:active{
	background: linear-gradient(0deg, #0074d8, #b6d6f7);
	}
	.required{
	padding: 0.5em;
	font-size: 0.9em;
	color: #ff0000;
}
</style>
	<form method="post" action="">
		メールアドレス<span class="required">*必須</span><input type="email" name="ID" value="" required><br>
		パスワード<span class="required">*必須</span>　　　　<input type="password" name="password" required><br>
		パスワード<span class="required">*必須</span>　　　　<input type="password" name="password2" value="" placeholder="もう一度入力してください" required><br>
		お名前(公開されます)<span class="required">*必須</span><input type="text" name="name" required><br>
	
	<?php			// 登録ボタンが押されたら、登録情報を取得する。
		if(isset($_POST['registration'])){
			$new_id=$_POST['ID'];
			if($_POST['password']===$_POST['password2'])
				$new_password=$_POST['password'];
			$new_name=$_POST['name'];
		}
	?>
	
	
	
	
	<input type="submit" value="登録" name="registration"><br><br>
	
	<?php
	//  登録ボタンが押された場合
	if(isset($_POST['registration'])){
		$sql = 'SELECT * FROM login_table';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		$flag=0;
		foreach ($results as $row){
			if($row['login_id']===$new_id){			// 登録済みのIDの場合
				echo "登録済みのメールアドレスです".'<br>';
				$flag=1;
				break;
			}
		}
		// 未登録のIDなので登録を行う
		if($flag===0 && $new_id!="" && $new_password!="" && $new_name!=""){
			$sql=$pdo->prepare("INSERT INTO login_table (login_id,password,name) VALUES (:login_id, :password, :name)");
			$sql->bindParam(':login_id',$new_id,PDO::PARAM_STR);
			$sql->bindParam(':password',$new_password,PDO::PARAM_STR);
			$sql->bindParam(':name',$new_name,PDO::PARAM_STR);
			$sql->execute();		//挿入を実行
			
			echo "登録完了".'<br>';
		}
		
	}
	
	?>
	
	<a href="mission_6_login.php">
		<p>ログイン画面へ</p>
	</a>
	</form>
	

</body>
</html>
