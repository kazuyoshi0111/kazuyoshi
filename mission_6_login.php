<!DOCTYPE html>
<html lng="ja">
<head>
<meta charset="utf-8">
<title>掲示板</title>
</head>
<body>
<?php				
	//データベースに接続
	$dsn = 'mysql:dbname=tb********;host=localhost;host=localhost';
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

	<h1>掲示板</h1>	

<style>
/* ========= 掲示板 ============ */
h1 {
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
</style>
	<form method="post" action="mission_6_check.php">
		メールアドレス<input type="email" name="login_id" value=""><br>
		パスワード<input type="password" name="login_pass"><br>
		<input type="submit" value="ログイン" name="login"><br>

	<a href="mission_6_shinki.php">
			<p>新規登録</p>
		</a>
	</form>
		
		
		
		<?php
			/*
				// テーブル内の要素を表示
				$sql = 'SELECT * FROM login_table';
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				foreach ($results as $row){
					//テーブルの要素を列ごとに出力
					echo $row['id'].',    ';
					echo $row['login_id'].',    ';
					echo $row['password'].',    ';
					echo $row['name'];
					echo '<br>';
					echo "<hr>";
				}
				
				*/
			?>
			
		

</body>

</html>
