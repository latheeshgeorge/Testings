<?php

include_once( 'ofc-library/open-flash-chart.php' );

// generate some random data
srand((double)microtime()*1000000);

$months = array( 'January','February','March','April','May','June','July','August','September','October' );
$year = '2006';

$bar = new bar_sketch( 55, 6, '#d070ac', '#000000' );
$bar->key( $year, 10 );

for( $i=0; $i<10; $i++ )
{
    $tmp = rand(2,9);
    $tip = 'Title!<br>Test weird characters: &,%,�,$<br>'. $year .', '. $months[$i] .' = '. $tmp;
    $bar->add_data_tip( $tmp, $tip );
}


$g = new graph();
$g->title( 'Sketch', '{font-size:20px; color: #ffffff; margin:10px; background-color: #d070ac; padding: 5px 15px 5px 15px;}' );
$g->bg_colour = '#FDFDFD';


//
// add the bar object to the graph
//
$g->data_sets[] = $bar;

//
// LOOK!!! this is the important bit:
//
$g->set_tool_tip( '#tip#' );
//
//
//

$g->x_axis_colour( '#e0e0e0', '#e0e0e0' );
$g->set_x_tick_size( 9 );
$g->y_axis_colour( '#e0e0e0', '#e0e0e0' );

$g->set_x_labels( $months );
$g->set_x_label_style( 11, '#303030', 2 );
$g->set_y_label_style( 11, '#303030', 2 );

$g->set_y_max( 10 );
$g->y_label_steps( 5 );
echo $g->render();
?>
