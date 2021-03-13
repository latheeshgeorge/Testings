<?
/** Paging.php
*/
//if( !PHPUSER )
  //define( "PHPUSER", 1 | 0 );

class Paging_new {
	
  /**
  * Number of result to show per page (decided by user)
  * @private
  * @type int
  */  
  var $int_num_result;  
  
  /**
  * Total number of items (SQL count from db)
  * @private
  * @type int
  */
  var $int_nbr_row;
  
  /**
  * Current position in recordset
  * @private
  * @type int
  */
  var $int_cur_position;
  
  /**
  * Extra argv of query string
  * @private
  * @type String
  */
  var $str_ext_argv;
  
	/**
	 ** The constructors simply gives an initial value to all private vars
	 ** @returns void
	 **/
  function Paging( $int_nbr_row, $int_cur_position, $int_num_result, $str_ext_argv = "" ){
    $this->int_nbr_row = $int_nbr_row;
    $this->int_num_result = $int_num_result;
    $this->int_cur_position = $int_cur_position;
    $this->str_ext_argv = urldecode( $str_ext_argv );
  }

	/**
   ** getNumberOfPage()
	 ** This function returns the total number of page to display.
	 ** @returns int
	 **/
  function getNumberOfPage(){
    $int_nbr_page = $this->int_nbr_row / $this->int_num_result;
    return $int_nbr_page;
  }
	
	/**
   ** getCurrentPage()
	 ** This function returns the current page number.
	 ** @returns int
  **/
  function getCurrentPage(){
    $int_cur_page = ( $this->int_cur_position * $this->getNumberOfPage() ) / $this->int_nbr_row;
    return number_format( $int_cur_page, 0 );
  }
  

	/**
   ** getPagingArray()
   ** Build an array represented by:
   ** $array_paging['lower'] lower limit of where we are in result set
   ** $array_paging['upper'] upper limit of where we are in result set
   ** $array_paging['total'] total number of result
   ** $array_paging['previous_link'] href tag for previous link
   ** $array_paging['next_link'] href tag for next link
	 ** @returns Array
  **/
  function getPagingArray($var_name,$path){
    global $PHP_SELF;

    // define the lower limit of all results
    $array_paging['lower'] = ( $this->int_cur_position + 1 );

    // define the upper limit of all results
    if( $this->int_cur_position + $this->int_num_result >= $this->int_nbr_row ){
      $array_paging['upper'] = $this->int_nbr_row;
    }else{
      $array_paging['upper'] = ( $this->int_cur_position + $this->int_num_result );
    }

    // define the total of results
    $array_paging['total'] = $this->int_nbr_row;

    // define the previous link (html href)
    if ( $this->int_cur_position != 0 ){
      $array_paging['previous_link'] = "<li><a href=\"$path?$var_name=". ( $this->int_cur_position - $this->int_num_result ).$this->str_ext_argv ."\">";
    }			

    // define the next link (html href)
    if( ( $this->int_nbr_row - $this->int_cur_position ) > $this->int_num_result ){
      $int_new_position = $this->int_cur_position + $this->int_num_result;	
      $array_paging['next_link'] = "<li><a href=\"$path?$var_name=$int_new_position". $this->str_ext_argv ."\">";
    }
    return $array_paging;
  }

	/**
   ** getPagingRowArray()
	 ** This function returns an array of string (href link with the page number)
	 ** @returns Array
  **/
  function getPagingRowArray($var_name,$cur_class){
    global $PHP_SELF;

    for( $i=0; $i<$this->getNumberOfPage(); $i++ ){
      
      // if current page, do not make a link
      if( $i == $this->getCurrentPage() ){
        $array_all_page[$i] = "<li class='".$cur_class."'>". ($i+1) ."</li>";
      }else{
        $int_new_position = ( $i * $this->int_num_result );
        $array_all_page[$i] = "<li><a href=\"". $path ."?$var_name=$int_new_position$this->str_ext_argv\">". ($i+1) ."</a></li>";
      }
    }
    return $array_all_page;
  }
  /* 
  	 ** Function which actually display the paging
  */
  function display_paging($prefix,$var_name='',$class_arr,$path='')
  {
  ?>
	<div class="<?php echo $class_arr['container']?>" align="center">
	<?php 
  	// Load up the 2 array in order to display result
	$array_paging 		= $this->getPagingArray($var_name,$path);
	$array_row_paging 	= $this->getPagingRowArray($var_name,$class_arr['current']);
  	print "$prefix ". $array_paging['lower'];
	print " to ". $array_paging['upper'];
	print " of ". $array_paging['total'];
?>
    <ul class="<?php echo $class_arr['navvul']?>">
<?php
	print "&nbsp;&nbsp;". $array_paging['previous_link'] ."<<<</a></li>" ;
	for( $i=0; $i<sizeof($array_row_paging); $i++ ){
	  print $array_row_paging[$i] ."&nbsp;";
	}
	print $array_paging['next_link'] .">>></a></li>";
	?>
	 </ul>
	</div>
	<?php
  }
}; // End Class

// ==============================================================
// Exemple Usage
// Note: I make 2 query to the database for this exemple, it
// could (and should) be made with only one query...
// ==============================================================
/*
include( "db_mysql.php" );

// New instance of database object, from phplib (http://phplib.sourceforge.net/)
$db = new db_data();

// If current position is not set, set it to zero
if( !isset( $int_cur_position ) || $int_cur_position == 0 ){
  $int_cur_position = 0;
}

// Number of result to display on the page, will be in the LIMIT of the sql query also
$int_num_result = 10;
$extargv = "&argv1=1&argv2=2"; // extra argv here (could be anything depending on your page)

// Get the total number of result from db
$sql1 = "Select count( key ) as nbr FROM table";

if ( !$db->query( $sql1 )){ print "\n<p>ERREUR $sql1"; exit; }
$db->next_record();
$result_from_sql1 = $db->f("nbr");

// New instance of the Paging class, you can modify the color and the width of the html table
$p = new Paging( $result_from_sql1, $int_cur_position, $int_num_result, $extargv );

// Load up the 2 array in order to display result
$array_paging = $p->getPagingArray();
$array_row_paging = $p->getPagingRowArray();

// Display the result as you like...
print "Results: ". $array_paging['lower'];
print " to ". $array_paging['upper'];
print " of ". $array_paging['total'];
print "&nbsp;&nbsp;". $array_paging['previous_link'] ."<<<</a> " ;
for( $i=0; $i<sizeof($array_row_paging); $i++ ){
  print $array_row_paging[$i] ."&nbsp;";
}
print $array_paging['next_link'] .">>></a>";

// The above exemple will print somethings like:
// Results: 1 to 10 of 597  <<< 1 2 3 4 5 6 7 8 9 10 >>>
// Of course you can now play with array_row_paging in order to print
// only the results you would like...

// Now go on with the rest of you page...
//  Select only fields needed according to paging
$sql2 = "Select * FROM my_table ORDER BY id "
."LIMIT $int_cur_position, $int_num_result";
*/
?>
