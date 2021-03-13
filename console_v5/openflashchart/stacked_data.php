<?php

include_once 'php-ofc-library/open-flash-chart.php';

$animation_1= isset($_GET['animation_1'])?$_GET['animation_1']:'pop';
$delay_1    = isset($_GET['delay_1'])?$_GET['delay_1']:0.5;
$cascade_1    = isset($_GET['cascade_1'])?$_GET['cascade_1']:1;

$title = new title( "COMPARISON OF STATUTORY MINIMUM DAYS' LEAVE AND PUBLIC HOLIDAYS ACROSS EUROPE" );
$title->set_style( "{font-size: 12px; color: #F24062; text-align: center;}" );

$bar_stack = new bar_stack();

$bar_stack->set_colours( array( '#C4D318', '#50284A' ) );
$bar_stack->set_keys(array(
        new bar_stack_key( '#C4D318', 'STATUTORY MINIMUM', 13 ),
        new bar_stack_key( '#50284A', 'PUBLIC HOLIDAYS', 13 )
        ));

$bar_stack->append_stack(array(25,14));
$bar_stack->append_stack(array(25,13));
$bar_stack->append_stack(array(25,12));
$bar_stack->append_stack(array(25,11));
$bar_stack->append_stack(array(22,14));
$bar_stack->append_stack(array(22,14));
$bar_stack->append_stack(array(25,11));
$bar_stack->append_stack(array(25,10));
$bar_stack->append_stack(array(25,10));
$bar_stack->append_stack(array(20,13));
$bar_stack->append_stack(array(20,10));
$bar_stack->append_stack(array(20,10));
$bar_stack->append_stack(array(20,9));
$bar_stack->append_stack(array(20,8));
$bar_stack->append_stack(array(20,8));

$bar_stack->set_tooltip( 'In #x_label# you get #total# days holiday a year.<br>Number of days: #val#' );

$bar_stack->set_on_show(new bar_on_show($animation_1, $cascade_1, $delay_1));

$y = new y_axis();
$y->set_range( 0, 50, 10 );


$x_labels = new x_axis_labels();
$x_labels->rotate(20);
$x_labels->set_labels(array(
    'Finland', 'Austria',
    'Greece', 'France',
    'Portugal', 'Spain',
    'Sweden', 'Denmark',
    'Luxembourg', 'Germany',
    'Belgium', 'Italy',
    'Ireland', 'Netherlands', 'UK'            
    ));
$x = new x_axis();
$x->set_labels($x_labels);
    
$tooltip = new tooltip();
$tooltip->set_hover();

$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $bar_stack );
$chart->set_x_axis( $x );
$chart->add_y_axis( $y );
$chart->set_tooltip( $tooltip );

echo $chart->toPrettyString();
