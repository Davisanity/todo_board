<html>
<title>Let me show You what you do</title>
<head><link rel=stylesheet type="text/css" href="css/board_view.css"></head>
<body background="Pics/board_back.jpg " background-repeat: repeat-x;>
<script type="text/javascript">
  var table = "";
  var month = "";
  function get_tb_name(){
    table = document.getElementById("choose_table").value;
    location.href="board.php?value="+table; 
  }
  function get_month(){
    table = document.getElementById("choose_table").value;
    month = document.getElementById("choose_month").value
    location.href="board.php?value="+table+"&month="+month;
    // alert(month);
    // $.ajax({
    //    type: "POST",
    //    url: "board.php",
    //    dataType: "text",
    //     data: {
    //       month:$("#choose_month").val(),
    //     },
    //     success: function(data) {
    //      alert("asdf");               
    //     },
    //     error: function(jqXHR) {
    //       alert("發生錯誤");
    //     }
    // })
    // alert("yes pressed")
  }
</script>  
  <h1 id="content">
  <?php
  session_start();
  echo $_SESSION['l_id'];
  // SESSION是全局变量，也就是说，它只要被声明，在所有页面都是可用的，前提是你不关闭网页或者没有到SESSION的生命周期
  ?>
  ,來看看這些日子你做了什麼吧
  </h1>
  <a href="profile.php"  title="back">點我返回表單</a>
  <!--上面那行如果加上 target="_blank"  會變成開新分頁-->
  <a >選擇資料</a>
  <table name="board" bgcolor="00FF99">
  <?php
  require_once('connsql.php');
  $conn=mysqli_connect($servername,$username,$password,$dbname);
  /* line 49~78 找有多少table ******** */
  $sql = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'add_todo' AND engine = 'InnoDB' AND TABLE_NAME!='user' ORDER by TABLE_NAME DESC";
  // information_schema.TABLES是固定用法 然後要找的table放在TABLE_SCHEMA 
  $query_result=mysqli_query($conn,$sql);
  // $table_records=mysqli_num_rows($query_result);  // 取得記錄數
  echo '<select id="choose_table" name="choose_table" onchange=get_tb_name()>';
  $i=0;
  $query_table=array();
  while ($r=mysqli_fetch_array($query_result)){
      $query_table[$i]=$r['TABLE_NAME'];
      if(isset($_GET['value']) && $_GET['value']==$query_table[$i] ){
        echo '<OPTION selected VALUE="'.$query_table[$i].'" >'.$query_table[$i];
      }else{
        echo '<OPTION VALUE="'.$query_table[$i].'" >'.$query_table[$i];
      }
      $i++;
  }
  if(isset($_GET['value'])){
    if($_GET['value']==''){
    $db_table=$query_table[0];  
    }
    else{
    $db_table=$_GET['value'];
    }
  }else{
    $db_table=$query_table[0];  
  }
  echo '</select>';
  $user_id = $_SESSION['l_id'];
  /*  **********************  */

  //SELECT MONTH(`dates`) FROM `2018first` GROUP BY MONTH(`dates`) 搜尋出所有月份
   //SELECT `dates` FROM `2018first` WHERE MONTH(`dates`) = 1 搜尋出1月的所有資料
  // $sql = "SELECT * FROM ".$db_table." WHERE `user_id` =  '".$user_id." 'AND MONTH(`dates`) = 1 ORDER by dates ASC" ; 
  /* line83~106 選擇table內的月份 **** */
  echo '<a >選擇月份</a>';
  $sql = "SELECT MONTH(`dates`) FROM `" .$db_table. "` GROUP BY MONTH(`dates`)";
  $query_result=mysqli_query($conn,$sql);
  echo '<select id="choose_month" name="choose_month" onchange=get_month() >';
  
  $i=0;
  $q_month='all';
  $query_month=array();
  echo '<OPTION  VALUE="all" >all';
  while ($r=mysqli_fetch_array($query_result)){
    $query_month[$i]=$r['MONTH(`dates`)'];
    
    if(isset($_GET['month']) && $_GET['month']==$query_month[$i]){
        echo '<OPTION selected VALUE="'.$query_month[$i].'" >'.$query_month[$i];
        $q_month=$_GET['month'];
      
    }else{
      echo '<OPTION VALUE="'.$query_month[$i].'" >'.$query_month[$i];
      
    }
    $i++;
  }
  echo '</select>';
  /*  **************  */
  // $db_note = ["","note_pro","note_reading","note_writing","note_englishing","note_movie","note_sport","note_other"];
  //如果有選月份 就是else那個
  if ($q_month == 'all'){
    $sql = "SELECT * FROM ".$db_table." WHERE `user_id` =  '".$user_id." ' ORDER by dates ASC" ; 
  }else{
    $sql = "SELECT * FROM ".$db_table." WHERE `user_id` =  '".$user_id." 'AND MONTH(`dates`) = '" .$q_month.  "' ORDER by dates ASC" ;
  }
  
  //在things資料表中選擇所有欄位
  mysqli_query($conn,  "SET collation_connection = ‘utf8_general_ci‘");
  $query_result = mysqli_query($conn,$sql); // 執行SQL查詢
  //$result = mysqli_fetch_assoc($query_result); //將陣列以欄位名索引
  //$total_fields=mysqli_num_fields($query_result); // 取得欄位數(會是8 因為有8個欄位)
  //@代表隱藏錯誤訊息
  @$total_records=mysqli_num_rows($query_result);  // 取得記錄數
  echo '<tr>';
    echo '<th>'."日期".'</th>';
    echo '<th>'."專業".'</th>';
    echo '<th>'."閱讀".'</th>';
    // echo '<th>'."寫字".'</th>';
    echo '<th>'."語文".'</th>';
    echo '<th>'."影片".'</th>';
    echo '<th>'."健身".'</th>';
    echo '<th>'."其他".'</th>';
  echo '</tr>';
  for($i=0;$i<$total_records;$i++){
    $result = mysqli_fetch_assoc($query_result); //將陣列以欄位名索引
    echo '<tr>';
      echo '<td>'.$result["dates"].'</td>';        //–印出id欄位的值
      echo '<td>'.$result["note_pro"].'</td>'; //<!–印出name欄位的值–>
      echo '<td>'.$result["note_reading"].'</td>';     //<!–印出age欄位的值–>
      // echo '<td>'.$result["note_writing"].'</td>';
      echo '<td>'.$result["note_language"].'</td>';
      echo '<td>'.$result["note_movie"].'</td>';
      echo '<td>'.$result["note_sport"].'</td>';
      echo '<td>'.$result["note_other"].'</td>';
    echo '</tr>';
  }

  ?>
  </table>
</body>
</html>
