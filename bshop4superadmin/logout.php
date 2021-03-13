<?php
$_SESSION['user_id']='';
unset($_SESSION['user_id']);
redirect('index.php',$arg = "logout=true");
?>