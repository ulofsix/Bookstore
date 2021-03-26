<?php
session_start();
/*路徑*/
/* 客戶端 */
$root_cust = "/bookstore";
/* 預設為空 */
// $root_cust = "";
/* 本機端 */
$root_local = $_SERVER['DOCUMENT_ROOT'] . $root_cust;
?>

<?php
//引入檔案
require_once($root_local . "/Connections/connSQL.php");
require_once($root_local . "/script/script.php");


if((!isset($_SESSION["login"])) || ($_SESSION["login"] == false)){
    jump_url("/results/login/login.php?cart=1","請先登入或註冊會員!");
} 

// 建立更新購物車 (須置頂)
if (isset($_POST['C_ID'])) {
    $countNum = count($_POST['qty']);
    for ($i = 0; $i < $countNum; $i++) {
      $qty = $_POST['qty'][$i];
      $C_ID = $_POST['C_ID'][$i];
      $updateSQL = sprintf("UPDATE cart SET qty='%d' WHERE C_ID='%d'", $qty, $C_ID);
      $result = mysqli_query($link, $updateSQL);
    }
  }

// 接收session中的產品清單，並加進購物車
if((isset($_SESSION["cart_PID"])) && ($_SESSION["login"] == "true")){
    $countNum = count($_SESSION['cart_PID']);
    for($i=0; $i<$countNum; $i++){
        $P_ID = $_SESSION['cart_PID'][$i];
        $qty = $_SESSION['cart_qty'][$i];
        $updateSQL = sprintf("INSERT INTO cart (UID, P_ID, qty) VALUES ('%s','%s','%s')", $_SESSION['UID'], $P_ID, $qty);
      $result = mysqli_query($link, $updateSQL);
    }
    unset($_SESSION['cart_PID']);
    unset($_SESSION['cart_qty']);
}


// 查詢UID
$query = sprintf("SELECT * FROM products,cart WHERE products.P_ID = cart.P_ID and cart.UID = %d", $_SESSION['UID']);
$result = mysqli_query($link, $query) or die("Error: " . mysqli_error($link));


$num = 0;
$total = 0;
?>


<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>南波灣書局</title>

    <!-- CSS -->
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="<?php echo $root_cust; ?>/main_flex.css">

    <!-- 正黑體 -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- 標籤LOGO -->
    <link rel="shortcut icon" href="<?php $root_cust; ?>/images/book-2.png">
    <link rel="bookmark" href="<?php $root_cust; ?>/images/book-2.png">

    <!-- 建立javascript詢問功能函數 -->
  <Script type="text/javascript">
    function btn_del(){
        let msg = "確定要刪除此項商品嗎？";
        if(confirm(msg)==true){
            return true;
        }else{
            return false;
        }
    }
  </Script>
</head>

<body>
    <!-- header -->
    <?php require_once($root_local . "/parts/header.php"); ?>

    <!-- main content -->
    <div id="main">
        <h3>我的購物車</h3>
        <hr width="95%" align="left">

        <div id="shopping_car">
            <div class="car_title">
                <div class="chead">書名</div>
                <div class="cqty">數量</div>
                <div class="cprice">單價</div>
                <div class="csale">活動折扣</div>
                <div class="ccount">小計</div>
                <div class="cdel">刪除</div>
            </div>

            <form action="cart.php" method="post" name="form1">
                <?php while ($data = mysqli_fetch_array($result)) { ?>
                    <div class="order_list">
                        <div class="cimg">
                            <img src="<?php echo $root_cust; ?>/images/product/<?php echo $data['P_ID']; ?>_1.jpg" alt="">
                        </div>
                        <div class="flex_box cname">
                            <div class="cname">
                                <?php echo $data['P_name'];
                                $num++; ?>
                            </div>
                            <div class="cauthor">
                                <?php echo $data['P_author']; ?>
                            </div>
                        </div>

                        <!-- 1/24  note:如何引用活動折扣function? -->
                        <div class="cqty">
                            <input type="text" name="qty[]" id="qty[]" size="1" maxlength="2" value="<?php echo $data['qty']; ?>">　件
                        </div>
                        <div class="cprice"><?php echo $data['P_price']; ?></div>
                        <div class="csale"><?php echo $data['P_price']; ?></div>
                        <div class="ccount"><?php echo $data['P_price'] * $data['qty']; ?></div>
                        <?php $total += $data['P_price'] * $data['qty']; ?>
                        <div class="cdel" style="font-size: 1.5rem">
                            <a href="cart_del.php?C_ID=<?php echo $data['C_ID']; ?>" onclick="javascript:return btn_del()"><img src="<?php echo $root_cust; ?>/results/cart/images/delete.png" alt="刪除" style="width:1.7vw"></a>
                            <input type="hidden" id="C_ID[]" name="C_ID[]" value="<?php echo $data['C_ID']; ?>">
                        </div>
                    </div>
                <?php }  ?>

                <div id="total">
                    <div class="cost">
                        <hr>
                        <p>共 <?php echo $num; ?> 件商品</p>
                        <p>小計：<?php echo $total; ?> 元</p>
                        <?php
                        if ($total < 1000) {
                            $fee = 60;
                            $message = "未達免運門檻";
                        } else {
                            $fee = 0;
                            $message = "已達免運門檻";
                        }
                        ?>
                        <p>運費：<?php echo $fee . "元<p><font size='1rem'>(" . $message . ")</font>"; ?></p>
                        <p>總金額：<?php $subtotal = $total + $fee;
                                echo $subtotal;  ?> 元</p>
                    </div>
                    <div class="flex_box" style="flex-direction:colum">
                        <input type="button" value="結帳去 GO!" class="btn_pay" onclick="location.href='<?php echo $root_cust; ?>/results/cart/uorder.php'">
                        <input type="submit" id="button[]" name="button[]" value="更新購物車" class="btn_pay" onclick="alert('購物車即將更新!')">
                        <input type="button" value="繼續購物" onclick="location.href='<?php echo $root_cust; ?>/index.php'" class="btn_pay">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- 頁尾 -->


    <div id="footer">
        Copyright © since 2020 No.1 books.com.tw All Rights Reserved.
    </div>

</body>

</html>