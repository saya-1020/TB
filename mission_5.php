<?php
//データベースへ接続
$dsn = 'データベース名';
//データソース名=mysql:(接頭辞)dbname=(データベース名)=host(データベースサーバーが存在するホスト名)
$user = 'ユーザー名';//MySQLのユーザー名
$password = 'パスワード';//$userのパスワード
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));//エラー発見を簡単にする


//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS etable"//etableを作成

." ("
." id INT AUTO_INCREMENT PRIMARY KEY,"//idとして投稿番号をつける
." name TEXT,"//nameとして文字列を入れるというデータ型
." comment TEXT,"//commentとして文字列を入れるというデータ型
." date TEXT,"//createdとして文字列を入れるというデータ型
." pass TEXT"//passとして文字列を入れるというデータ型
.") ";
$stmt = $pdo->query($sql);//query=実行
//$stmtを使うとき=実行後にSQLの実行結果に関する情報を得たい場合

/*
//テーブルの中身を表示するコマンドを使って作成ができたか確認する
$sql = 'SHOW CREATE TABLE etable';//テーブルの中身を表示する
$result = $pdo -> query($sql);//query=実行
//上で引数に指定したsql文をデータベースに対して発行してくれる
foreach ($result as $row){
         echo $row[1];
         echo '<br>';
}
echo "<hr>";*/
?>

<html>
<head>

  <title>mission_5入力フォーム</title>
  <meta charset = "utf-8">
</head>
<body>

<?php
//編集フォーム
if(!empty($_POST["henkou"]) and !empty($_POST["passb"]))
{
 $hensyu = $_POST["henkou"];
 $pass = $_POST["passb"];

 //入力された内容をselectで取得する
 $sql = 'SELECT * FROM etable';//etableを表示
 $stmt = $pdo -> query($sql);//stmtを使用して実行後に実行結果に関する情報を得られるようにする
 $results = $stmt -> fetchAll();//fetchAll→全ての結果行を含む配列を返す

    foreach ($results as $row)//一つずつ取得

    if($row['id'] == $hensyu and $row['pass'] == $pass)//番号とパスワードが一致していたら内容を取得
    //氏名とコメントを変数に定義
    {
      $kaeruname = $row['name'];
      $kaerukome = $row['comment'];
    }
    else
    {}
}
else
{}
//ここまで投稿フォームに編集したい番号の内容を表示させる処理
?>




<h2>好きな映画はなんですか？</h2>
<br>
<入力欄>
    <form action = "mission_5.php" method = "post">
<input type = "hidden" name = "bangou" value = "<?php if(!empty($hensyu)){echo $hensyu;} else{} ?>" >
  <br>
氏名:
<input type = "text" name = "name" value = "<?php if(!empty($hensyu) and !empty($kaeruname)){echo $kaeruname;} else{} ?>" placeholder = "氏名">
  <br>
タイトル:
<input type = "text" name = "comment" value = "<?php if(!empty($hensyu) and !empty($kaerukome)){echo $kaerukome;} else{} ?>" placeholder = "タイトル">
  <br>
パスワード:
<input type = "text" name = "pass" value = "" placeholder = "パスワード" >
 <br>
<input type = "submit" name = "sousin" value = "送信" >

    </form>


    <form action = "mission_5.php" method = "post">
<削除フォーム>

<br>
削除:
<input type = "text" name = "delete" value = "" placeholder = "番号">
<br>
パスワード:
<input type = "text" name = "passa" value = "" placeholder = "パスワード" >
<br>
            <input type = "submit" name = "kesu" value = "削除">

    </form>


    <form action = "mission_5.php" method = "post">
<編集フォーム>
<br>
編集:
<input type = "text" name = "henkou" value = "" placeholder = "番号">
<br>
パスワード:
<input type = "text" name = "passb" value = "" placeholder = "パスワード" >
<br>
            <input type = "submit" name = "hensyu" value = "編集">

    </form>


<?php

//新規投稿フォーム
//作成したテーブルにinsertを行ってデータを入力する
if(!empty($_POST["name"]) and !empty($_POST["comment"]) and !empty($_POST["pass"]) and empty($_POST["bangou"]))
//記入欄が空白でなく、編集フォームから送信されたものでない場合
{
  $sql = $pdo -> prepare("INSERT INTO etable (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
  //prepareはexecuteを後に入れないと実行されない
  $sql -> bindparam(':name',$name,PDO::PARAM_STR);//:nameに$nameを入れる。文字列
  $sql -> bindparam(':comment', $comment, PDO::PARAM_STR);//:commentに$commentを入れる。文字列
  $sql -> bindparam(':date', $date,  PDO::PARAM_STR);//:dateに$dateを入れる。文字列
  $sql -> bindparam(':pass', $pass, PDO::PARAM_STR);//:passに$passを入れる。文字列
  /*
  bindparam('引数を指定', 代入する変数を指定, 型を指定→PDO::PARAM_STRは「文字列だよ」ということ)
  $ここに'これ'を入れるよって時に使う
  下で$に代入するものを定義
  */
  $name = $_POST["name"];//名前を入れる
  $comment = $_POST["comment"];//コメントを入れる
  $date = date("Y年m月d日 H時i分s秒");//date関数で日時を取得して入れる
  $pass = $_POST["pass"];//パスワードを入れる
  
  $sql -> execute();//execute→命令を実行
}
else
{}

//削除フォーム
if(!empty($_POST["delete"]))//削除フォームが空欄でない場合
{
 //passの照合
 //入力された内容をselectで取得する
 $sql = 'SELECT * FROM etable';//etableを取得
 $stmt = $pdo -> query($sql);//stmtを使用して実行後に実行結果に関する情報を得られるようにする
 $results = $stmt -> fetchAll();//fetchAll→全ての結果行を含む配列を返す

    foreach ($results as $row)//一つずつ取得
 {
  if($row['id'] == $_POST["delete"] and $row['pass'] == $_POST["passa"])//パスワードが一致する場合
   {
    $id = $_POST["delete"];//$idに削除したい番号を代入
    $pass = $_POST["passa"];//$passに削除フォームで入力したパスワードを代入
    $sql = 'delete from etable where id =:id and pass =:pass';//DELETE文で条件を定義

    $stmt = $pdo ->prepare($sql);//DELETEをexecuteで実行する。
    $stmt ->bindparam(':id', $id, PDO::PARAM_INT);//$idに:idを代入
    $stmt ->bindparam(':pass', $pass, PDO::PARAM_STR);//$passに:passを代入
    $stmt ->execute();//実行
   }
  else
   {}//if関数がなくてもできた。
 }
}
else
{}


//編集フォーム
//updateを使って編集内容を反映する
if(!empty($_POST["bangou"]) and !empty($_POST["name"]) and !empty($_POST["comment"]) and !empty($_POST["pass"]))
//編集対象の欄、記入欄が空白でない場合
{
 //入力された内容をselectで取得する
 $sql = 'SELECT * FROM etable';//etableを取得
 $stmt = $pdo -> query($sql);//stmtを使用して実行後に実行結果に関する情報を得られるようにする
 $results = $stmt -> fetchAll();//fetchAll→全ての結果行を含む配列を返す

    foreach ($results as $row)//一つずつ取得

  if($row['id'] == $_POST["bangou"])//投稿番号が編集フォームで送った番号と一致するか
  {  $id = $_POST["bangou"]; //変更する投稿番号(入力フォームのhiddenに入ってる番号)
     $name = $_POST["name"];//変更したい名前を入れる
     $comment = $_POST["comment"];//変更したいコメントを入れる
     $pass = $_POST["pass"];//変更したいパスワードを入れる

    $sql = 'update etable set name=:name, comment=:comment, pass=:pass where id=:id';
//idが一致すれば変更する
/*
UPDATE文
update テーブル名 set 列名1=値1 [,列名2=値2]... where (条件);
whereがない場合、全ての行を更新する
*/
    $stmt = $pdo -> prepare($sql);
    $stmt ->bindparam(':name', $name, PDO::PARAM_STR);//$nameに':name'を入れる
    $stmt ->bindparam(':comment', $comment, PDO::PARAM_STR);//$commentに':comment'を入れる
    $stmt ->bindparam(':pass', $pass, PDO::PARAM_STR);//$passに':pass'を入れる
    $stmt ->bindparam(':id', $id, PDO::PARAM_INT);//$idに';id'を入れる
    $stmt ->execute();//命令を実行(insert)
  }
  else
  {}
}
else
{}


//表示する
$sql = 'SELECT * FROM etable';//dtableを表示
$stmt = $pdo -> query($sql);//stmtを使用して実行後に実行結果に関する情報を得られるようにする
$results = $stmt -> fetchAll();//fetchAll→全ての結果行を含む配列を返す

foreach ($results as $row){//一つずつ取得
         //$rowにはテーブルのカラム名が入る
         echo $row['id'].',';//'id'は上でAUTO_INCREMENTを使って+1するようにしたカラム。投稿番号
         echo $row['name'].',';//nameを表示
         echo $row['comment'];//commentを表示
         echo $row['date'].'<br>';//日時を表示
echo "<hr>";
}
?>
