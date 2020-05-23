<!DOCTYPE html>
<html lng="ja">
<head>
	<meta charset="utf-8">
	<title>掲示板</title>
</head>

<body>
 <div class="header">
  <div class="header-logo">掲示板</div>
   <div class="header-list">
    <ul>
     <li> <a href="mission_6_login.php">ログアウト</a></li>
    </ul>
   </div>
 </div>
<style>
body {
  font-family: "Avenir Next";
}

li {
  list-style: none;
  float: right;
  /* 上下のpaddingを33px、左右のpaddingを20pxにしてください */
  padding: 33px 20px;
  
}

.header {
  background-color: #26d0c9;
  color: #fff;
  height: 90px;
}

.header-logo {
  float: left;
  font-size: 36px;
  /* 上下のpaddingを20px、左右のpaddingを40pxにしてください */
  padding: 20px 40px;
  
}

.main {
  background-color: #bdf7f1;
  height: 600px;
}

.footer {
  background-color: #ceccf3;
  height: 270px;
}

</style>
	<?php				
	//データベースに接続
	$dsn = 'mysql:dbname=tb******db;host=localhost;host=localhost';
	$user = 'tb-*******';
	$password = '***********';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	//  投稿を管理するテーブルを作成(なければ)
	$sql = "CREATE TABLE IF NOT EXISTS instagram3"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"		//番号はINT
	. "fname TEXT,"					//ファイル名
	. "extension TEXT,"				//拡張子
	. "raw_data LONGBLOB,"				//バイナリデータ
	. "comment TEXT,"				//コメント
	. "name char(32),"				//名前
	. "date TEXT"															//日付
	.");";
	$stmt = $pdo->query($sql);	
	?>
	

	<?php
	if(isset($_POST['next'])){
		$user_name=$_POST['hidden_name'];
	}
	
	if(isset($_POST['name'])){
		$user_name=$_POST['name'];
	}
	?>
	

	<form method="post" action="" enctype="multipart/form-data">
		<br>
		<input type="hidden" name="name" value="<?php if(isset($user_name)){echo $user_name;} ?>"><br>
		<label>コメント</label><br>
		<textarea name="com"></textarea><br><br>
		<label>画像・動画をアップロード</label><br>
		※jpeg、png、gif、mp4にのみ対応<br>
		<input type="file" name="upfile"><br><br>
		<input type="submit" value="投稿" name="send"><br><br>
		
		<label>削除対象番号</label><br>
		<input type="text" name="deletenumber" value=""><br>
		<input type="submit" value="削除" name="delete"><br><br>
		
		<?php
		
		$comment="";
		$delete_num="";
		$date=date("Y/m/d H:i:s");
		
		
		//投稿ボタンが押された
		if(isset($_POST['send'])){
			if(isset($_POST["com"]))
				$comment=$_POST["com"];
		
			try{
								
				//ファイルのアップロードがあった時
				//エラーがセットされてる、それが整数値、ファイル名が指定されてる
				if (isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES["upfile"]["name"] !== ""){
					
					//エラーを判定する
					switch ($_FILES['upfile']['error']){
						case UPLOAD_ERR_OK:		//問題なし
							break;
				
						case UPLOAD_ERR_NO_FILE:			//ファイルが選択されていない
							throw new RuntimeException('ファイルが選択されていません', 400);
					
						case UPLOAD_ERR_INI_SIZE:  		// ファイルサイズがオーバー
							throw new RuntimeException('ファイルサイズが大きすぎます', 400);
						
						default:													//それ以外
							throw new RuntimeException('予期せぬエラーが発生しました', 500);
					}
					//アップされたファイルのバイナリデータを取得
					$raw_data=file_get_contents($_FILES['upfile']['tmp_name']);
			
					//アップされたファイルの拡張子を得る(連想配列に格納されている)
					$ex = pathinfo($_FILES["upfile"]["name"]);
					$extension = $ex["extension"];
					if($extension==="jpg" || $extension==="JPG" || $extension==="jpeg" || $extension==="JPEG")
						$extension="jpeg";			//jpegに統一
				
					elseif($extension === "png" || $extension === "PNG")
						$extension="png";			//pngに統一
				
					elseif($extension === "gif" || $extension === "GIF")
						$extension = "gif";			//gifに統一
				
					elseif($extension === "mp4" || $extension === "MP4")
						$extension = "mp4";			//mp4に統一
			
					else{				//対応していない拡張子の時は戻るボタンを表示
						echo "非対応のファイルです<br />";
						echo '<input type="button" onclick="history.back()" value="戻る">';
						exit(1);
					}
				
					$fname = $_FILES["upfile"]["tmp_name"];
		
					$sql=$pdo->prepare("INSERT INTO instagram3 (fname,extension,raw_data,comment,date,name) VALUES (:fname, :extension, :raw_data, :comment, :date, :name)");
					$sql->bindValue(':fname',$fname,PDO::PARAM_STR);
					$sql->bindValue(':extension',$extension,PDO::PARAM_STR);
					$sql->bindValue(':raw_data',$raw_data,PDO::PARAM_LOB);
					$sql->bindValue(':comment',$comment,PDO::PARAM_STR);
					$sql->bindValue(':date',$date,PDO::PARAM_STR);
					$sql->bindValue(':name',$user_name,PDO::PARAM_STR);
					$sql->execute();		//挿入を実行
				}
			}
			//例外処理
			catch(PDOException $e){
				echo("<p>500 Inertnal Server Error</p>");
				exit($e->getMessage());
			}
		}
		
		?>
		
		
		<?php
		
		//削除ボタンが押された時
			if(isset($_POST['delete'])){
				$delete_num=$_POST['deletenumber'];
				if($delete_num!=""){
					$sql = 'SELECT * FROM instagram3';
					$stmt = $pdo->query($sql);
					$results = $stmt->fetchAll();
					// 指定番号とパスワードが一致したらテーブルから削除する
					foreach ($results as $row){
						if($delete_num===$row['id'] && $user_name===$row['name']){
							$sql = 'delete from instagram3 where id=:id';
							$stmt = $pdo->prepare($sql);
							$stmt->bindParam(':id', $delete_num, PDO::PARAM_INT);
							$stmt->execute();
						}
					}
				}
			}
		
		
		
		?>
		
<h2>--投稿表示--</h2>
		<?php
		
				$sql = 'SELECT * FROM instagram3';
				$stmt = $pdo->query($sql);
				$results = $stmt->fetchAll();
				foreach ($results as $row){
					//テーブルの要素を出力
					echo ($row["id"]."<br/>");
					echo $row['name']."            ".$row['date'].'<br>';		//名前と日付
					echo $row['comment'].'<br>';					//コメント
					//echo $row['fname'].'<br>';					//fname一応
					//echo $row['extension'].'<br>';				//拡張子も一応
					
					$id=$row['id'];
					//動画の場合
					if($row["extension"] === "mp4"){
						$r=base64_encode($row["raw_data"]);
						$e=$row["extension"];
						
						echo '<div content="Content-Type: video/mp4">
							<video width="600" height="480" controls="controls" poster="image" preload="metadata">
								<source src="data:video/mp4;base64,'.base64_encode($row["raw_data"]).'"/>;
							</video>
							</div>';
					}
					//画像の場合
					elseif($row["extension"] === "jpeg" || $row["extension"] === "png" || $row["extension"] === "gif"){
						$r=base64_encode($row["raw_data"]);
						$e=$row["extension"];
						echo ("<img src='data:image/{$e};base64,{$r}'>");
					}
					echo ("<br/><br/>");
				}
		
		
			?>

</body>
</html>
