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

// 抓UID
if (isset($_SESSION['UID'])) {
    $UID = $_SESSION['UID'];
    $query = sprintf("SELECT * FROM products, cart, users WHERE products.P_ID=cart.P_ID AND cart.UID=users.UID AND cart.UID='%s'", $UID);
    $result = mysqli_query($link, $query) or die("Error: " . mysqli_error($link));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- css -->
    <link rel="stylesheet" href="<?php echo $root_cust; ?>/main_flex.css">
    <link rel="stylesheet" href="uorder.css">

    <!-- 正黑體 -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- 標籤LOGO -->
    <link rel="shortcut icon" href="<?php $root_cust; ?>/images/book-2.png">
    <link rel="bookmark" href="<?php $root_cust; ?>/images/book-2.png">
</head>

<body>

    <!-- header -->
    <?php require_once($root_local . "/parts/header.php"); ?>

    <!-- contnet -->
    <form action="" method="post" name="uorder_form">
        <div class="flex">
            <div class="order_list">
                <div class="title">
                    <div>書名<br>Product</div>
                    <div>數量<br>Quantity</div>
                    <div>優惠價<br>Special Price</div>
                </div>
                <div class="content">
                    <div>b書名</div>
                    <div>b數量</div>
                    <div>b優惠價</div>
                </div>
                <div class="total">
                    <div>商品件數：</div>
                    <div>折扣：</div>
                    <div>總金額：</div>
                </div>
            </div>
            <div class="pay_list">
                <table>
                    <tr>
                        <td colspan="2" style="text-align: center; color
                        :#666; font-size:1.1rem; border-bottom: 1px solid #999999; padding-bottom:10px;">收件人資訊</td>
                    </tr>
                    <tr>
                        <td>帳號：　</td>
                        <td><input type="text" name="uname" id="uname" ></td>
                    </tr>
                    <tr>
                        <td>收件人姓名：　</td>
                        <td><input type="text" name="oname" id="oname" ></td>
                    </tr>
                    <tr>
                        <td>收件地址：　</td>
                        <td><input type="text" name="oaddr" id="oaddr" ></td>
                    </tr>
                    <tr>
                        <td>運送方式：　</td>
                        <td>
                        <input type="radio" name="osend" id="osend" value="宅配" checked>宅配到家
                        <input type="radio" name="osend" id="osend" value="超商">超商取貨
                        </td>
                    </tr>
                    <tr>
                        <td>支付方式：　</td>
                        <td>
                        <input type="radio" name="opay" id="opay" value="貨到付款" checked>貨到付款
                        <input type="radio" name="opay" id="opay" value="匯款">匯款
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">訂單備註：　</td>
                        <td><textarea name="other" id="other" cols="30" rows="5"></textarea></td>
                    </tr>
                    <tr>
                        <td>注意事項：　</td>
                        <td>收件人姓名請填寫真實姓名，否則可能無法順利取貨。</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                        <input type="reset" value="重填">　
                        <input type="submit" value="確認">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>


</body>

</html>