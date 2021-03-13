<?php
include_once 'php-ofc-library/open-flash-chart.php';
$label[] = 'A';
$dataForGraph[] = 80;
$label[] = 'B';
$dataForGraph[] = 60;
$label[] = 'C';
$dataForGraph[] = 40;
$label[] = 'D';
$dataForGraph[] = 210;


$title = new title( 'The grades distribution : '.date("D M d Y").' are' );
$title->set_style( '{color: #567300; font-size: 14px}' );
$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->set_bg_colour( '#FFFFFF' );
 
$pie = new pie();
$pie->set_alpha(0.6);
$pie->set_start_angle( 35 );
$pie->add_animation( new pie_fade() );
$pie->set_tooltip( '#val# of #total#<br>#percent# of total strength' );
$pie->set_colours( array('#1C9E05','#FF368D','#1A3453','#1A3789') );
$pie->set_values( array(new pie_value($dataForGraph[0], "Grade" . $label[0]),
                new pie_value($dataForGraph[1], "Grade" . $label[1]),
                new pie_value($dataForGraph[2], "Grade" . $label[2]),
            new pie_value($dataForGraph[3], "Grade" . $label[3])) );
 
$chart->add_element( $pie );
echo $chart->toPrettyString();
?>
