<?php
$servername = "localhost";
// $servername = "192.168.20.4";
$username = "bookstore";
$password = "123456";
$database = "bookstore";

/* 參數位置 */
// $servername = "localhost";
// $username = "username";
// $password = "password";
// $database = "database";

/* Create connection */
$link = new mysqli($servername, $username, $password, $database);
// $link = mysqli_connect($servername, $username, $password, $database);




/* Check connection */
// if ($link->connect_error) {
//   die("Connection failed: " . $link->connect_error);
// }


// if ($link != FALSE) {
//   mysqli_query($link, "SET NAMES utf8");
//   // echo "OK";
// }

/* 連線失敗 */
if (mysqli_connect_errno()) {
  echo "連線失敗：" . mysqli_connect_error();
  $link = null;
  exit();
} else {
  /* 設定編碼 */
  mysqli_query($link, "SET NAMES utf8");
}
