<?php
 
include_once 'php-ofc-library/open-flash-chart.php';

$animation_1 = 'pop';
$delay_1     = 0.5;
$cascade_1   = 1;

$title = new title('');// "COMPARISON OF STATUTORY MINIMUM DAYS' LEAVE AND PUBLIC HOLIDAYS ACROSS EUROPE" );
//$title->set_style( "{font-size: 12px; color: #F24062; text-align: center;}" );

$bar_stack = new bar_stack();

$t = new tooltip('');
$t->set_shadow( false );
$t->set_stroke( 5 );
$t->set_colour( "#6E604F" );
$t->set_background_colour( "#BDB396" );
$t->set_title_style( "{font-size: 14px; color: #FFFFFF;}" );
$t->set_body_style( "{font-size: 10px; font-weight: bold; color: #000000;}" );

$bar_stack->set_colours( array( '#000000') );

$bar_stack->append_stack(array(25));
$bar_stack->append_stack(array(13));
$bar_stack->append_stack(array(12));
$bar_stack->append_stack(array(11));
$bar_stack->append_stack(array(14));
$bar_stack->append_stack(array(22));
$bar_stack->append_stack(array(11));
$bar_stack->append_stack(array(17));

$bar_stack->set_tooltip( '#x_label# | Order Count: #val#');


$bar_stack->set_on_show(new bar_on_show($animation_1, $cascade_1, $delay_1));

$y = new y_axis();
$y->set_range( 0, 50, 10 );
$y->set_grid_colour ('#914d4d');


$x_labels = new x_axis_labels();
$x_labels->rotate(40);
$x_labels->set_labels(array('January<br> Order Total: ' .html_entity_decode('&pound;').'3000 ','Feb ('.html_entity_decode('&pound;').'3000)','Mar ('.html_entity_decode('&pound;').'3000)','Apr ('.html_entity_decode('&pound;').'3000)','May ('.html_entity_decode('&pound;').'3000)','Jun ('.html_entity_decode('&pound;').'3000)','Jul ('.html_entity_decode('&pound;').'3000)','Aug ('.html_entity_decode('&pound;').'3000)'));
$x = new x_axis();
$x->set_labels($x_labels);
$x->set_grid_colour ('#914d4d');
    
$tooltip = new tooltip();
$tooltip->set_hover();
$tooltip->set_shadow( true );
$tooltip->set_stroke( 3 );
$tooltip->set_colour( "#ff0000" );
$tooltip->set_background_colour( "#ffffff" );
$tooltip->set_title_style( "{font-size: 10px; color: #000000;font-weight: bold;}" );
$tooltip->set_body_style( "{font-size: 10px; font-weight: bold; color: #000000;}" );


$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $bar_stack );
$chart->set_bg_colour( '#CCCCCC' );
$chart->set_x_axis( $x );
$chart->add_y_axis( $y );
$chart->set_tooltip( $tooltip );

echo $chart->toPrettyString();
