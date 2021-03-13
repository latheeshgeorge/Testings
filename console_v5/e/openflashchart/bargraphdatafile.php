<?php
include_once 'php-ofc-library/open-flash-chart.php';
$dataForGraph[] = 110;
$dataForGraph[] = 11;
$dataForGraph[] = 0;
$dataForGraph[] = 240;
$dataForGraph[] = 4;
$dataForGraph[] = 110;
$dataForGraph[] = 11;
$dataForGraph[] = 51;

$XAxisLabel[] = 'Jan';
$XAxisLabel[] = 'Feb';
$XAxisLabel[] = 'Mar';
$XAxisLabel[] = 'Apr';
$XAxisLabel[] = 'May';
$XAxisLabel[] = 'Jun';
$XAxisLabel[] = 'Jul';
$XAxisLabel[] = 'Aug<br>('.html_entity_decode('&pound;').'2000)';

$t = new tooltip( 'Hello<br>val = #val#' );
$t->set_shadow( false );
$t->set_stroke( 5 );
$t->set_colour( "#6E604F" );
$t->set_background_colour( "#BDB396" );
$t->set_title_style( "{font-size: 14px; color: #CC2A43;}" );
$t->set_body_style( "{font-size: 10px; font-weight: bold; color: #000000;}" );


$title = new title('');// 'The marks obtained by students as of : '.date("D M d Y").' are' );
//$title->set_style( '{color: #567300; font-size: 14px}' );
$chart = new open_flash_chart();
$chart->set_bg_colour( '#FFFFFF' );
$chart->set_title( $title );
$chart->set_tooltip( $t );
$x_axis_labels = new x_axis_labels();
$x_axis_labels->set_labels($XAxisLabel);
$y_axis = new y_axis();
$x_axis = new x_axis();
$y_axis->set_range( 0, 300, 20 );
$y_axis->set_grid_colour ('#914d4d');

$x_axis->set_labels ($x_axis_labels);
$x_axis->set_grid_colour ('#ffffff');

$chart->set_x_axis( $x_axis );
$chart->add_y_axis( $y_axis );


 
$bar = new bar_glass();
$bar->colour('#000000');
//$bar->key('Marks obtained', 12);
$bar->set_values($dataForGraph);
//$bar->set_tooltip(html_entity_decode('&pound;').'#val#');
$bar->set_tooltip('#val# - #x# - Orders');
$chart->add_element($bar);
mysql_close($link);
echo $chart->toPrettyString();
?>
