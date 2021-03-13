<?php
	$ecom_bypass_loggedin_check = 1; // done to bypass the "is logged in?" checking inside the session.php file
	include_once("functions/functions.php");
	include_once('session.php');
	include_once("config.php");
	include_once 'openflashchart/php-ofc-library/open-flash-chart.php';

// Building the data to be displayed in the graph
	$xaxis_arr = array();
	$data_arr = array();
	$max_val = 0;
	$month_arr = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
	$curr_symbol = '£';
	if($ecom_siteid==61)
		$gp_max = 15;
	else
		$gp_max = 8;
	for($i=0;$i<$gp_max;$i++)
	{
				$date = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-$i, date("Y")));
				$sql_order_total = "SELECT count(order_id) as cnt, SUM(order_totalprice) as tot 
												FROM 
													orders 
												WHERE 
													sites_site_id=$ecom_siteid 
													AND order_date >='$date 00:00:00' 
													AND order_date <= '$date 23:59:59' 
													AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
													AND order_paystatus NOT IN ('Pay_Failed','REFUNDED','PROTX','HSBC','GOOGLE_CHECKOUT','WORLD_PAY','ABLE2BUY','PROTX_VSP','REALEX','NOCHEX','PAYPAL_EXPRESS','PAYPALPRO') ";
				//echo $sql_order_total;
				$res_order_total = $db->query($sql_order_total);
				while($row_order_total = $db->fetch_array($res_order_total)) 
				{
					$date_arr = explode('-',$date);
					$date_str = $date_arr[2].' - '.$month_arr[$date_arr[1]];//.' - '.$date_arr[0]; 
					$tot = ($row_order_total['tot'])?$row_order_total['tot']:'0';
					$cnt = intval(($row_order_total['cnt'])?$row_order_total['cnt']:0);
					if($cnt>$max_val) $max_val = $cnt;
					$xaxis_arr[] = $date_str.' <br>Total: '.$curr_symbol.$tot;
					$data_arr[] = $cnt;
				}
			  
	}
$xaxis_arr[] = ' ';
$data_arr[] = 0;
$xaxis_arr[] = ' ';
$data_arr[] = 0;
$animation_1 = 'pop';
$delay_1     = 0.3;
$cascade_1   = 1;

$title = new title("Orders By Day (Past ".($gp_max-1)." days)" );
$title->set_style( "{font-weight:bold;font-size: 16px; color: #000000; text-align: center; margin-bottom:5px;margin-top:5px;}" );

$bar_stack = new bar_stack();

$t = new tooltip('');
$t->set_shadow( true );
$t->set_stroke( 5 );
$t->set_colour( "#6E604F" );
$t->set_background_colour( "#BDB396" );
$t->set_title_style( "{font-size: 14px; color: #FFFFFF;}" );
$t->set_body_style( "{font-size: 10px; font-weight: bold; color: #000000;}" );

$bar_stack->set_colours( array( '#425261','#59c8c5','#c8a259','#6b59c8','#c85978','#75c859') );

for($i=0;$i<count($data_arr);$i++)
{
	$bar_stack->append_stack(array($data_arr[$i]));
}
$bar_stack->set_tooltip( '#x_label# <br>Count: #val#');


$bar_stack->set_on_show(new bar_on_show($animation_1, $cascade_1, $delay_1));

$y = new y_axis();
$y->set_range( 0, ($max_val+40), 10 );
$y->set_grid_colour ('#dbdbdb');


$x_labels = new x_axis_labels();
$x_labels->rotate(20);
$x_labels->set_labels($xaxis_arr);
$x_labels->set_colour( '#474747' );
$x = new x_axis();
$x->set_labels($x_labels);
$x->set_grid_colour ('#dbdbdb');
    
$tooltip = new tooltip();
$tooltip->set_hover();
$tooltip->set_shadow( true );
$tooltip->set_stroke( 3 );
$tooltip->set_colour( "#000000" );
$tooltip->set_background_colour( "#FFCC00" );
$tooltip->set_title_style( "{font-size: 11px; color: #000000;font-weight: bold;}" );
$tooltip->set_body_style( "{font-size: 11px; font-weight: normal; color: #000000;}" );


$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $bar_stack );
$chart->set_bg_colour( '#FFFFFF' );
$chart->set_x_axis( $x );
$chart->add_y_axis( $y );
$chart->set_tooltip( $tooltip );

echo $chart->toPrettyString();
