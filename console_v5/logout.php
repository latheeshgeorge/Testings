<?php
$_SESSION['Menu_Items_'.$_SESSION['console_id']] = '';
$_SESSION['console_id']='';
$_SESSION['site_id']='';
$_SESSION['domain'] = '';
$_SESSION['user_type'] = '';

unset($_SESSION['site_id']);
unset($_SESSION['console_id']);
unset($_SESSION['domain']);
unset($_SESSION['user_type']);
unset($_SESSION['Menu_Items_'.$_SESSION['console_id']]);

redirect('index.php',$arg = "logout=true");
?>