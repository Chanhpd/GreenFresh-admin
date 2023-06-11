<?php
require("../DB/dbhelper.php");
require('../vendor/autoload.php');
require("../config.php");

use Cloudinary\Api\Upload\UploadApi;

$id = $_POST['id'];
$name = $_POST['name'];
$desc = $_POST['desc'];
$price = $_POST['price'];
$sale = $_POST['sale'];
$timeUpdate =
     date("Y/m/d");
$srcImg = $_POST['srcImg'];
$data =   (new UploadApi())->upload($srcImg);
$url = $data['secure_url'];

echo $sql = "UPDATE `product` SET `name` = '$name', `price` = '$price', `sale` = '$sale', `description` = '$desc',`thumb` = '$url',`updated_at` = '$timeUpdate' WHERE `product`.`id` = $id";

echo "<script>alert('" . $sql . "');</script>";
execute($sql);