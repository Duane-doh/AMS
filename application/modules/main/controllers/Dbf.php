<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dbf extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		print("DBF !!!");
		
		# Constants for dbf field types
		define ('BOOLEAN_FIELD',   'L');
		define ('CHARACTER_FIELD', 'C');
		define ('DATE_FIELD',      'D');
		define ('NUMBER_FIELD',    'N');
		
		# Constants for dbf file open modes
		define ('READ_ONLY',  '0');
		define ('WRITE_ONLY', '1');
		define ('READ_WRITE', '2');
		
		# Path to dbf file
		$db_file = 'C:/tmp/sushi_eaten.dbf';
		
		# dbf database definition
		# Each element in the first level of the array represents a row
		# Each array stored in the various elements represent the properties for the row
		$dbase_definition = array (
		   array ('name',  CHARACTER_FIELD,  20),  # string
		   array ('date',  DATE_FIELD),            # date yyymmdd
		   array ('desc',  CHARACTER_FIELD,  45),  # string
		   array ('cost',  NUMBER_FIELD, 5, 2),    # number (length, precision)
		   array ('good?', BOOLEAN_FIELD)          # boolean
		);
		
		# Records to insert into the dbf file   
		$inari = array ('Inari', 19991231, 'Deep-fried tofu pouches filled with rice.', 1.00, TRUE);
		$unagi = array ('Unagi', 19991231, 'Freshwater Eel', 2.50, FALSE);
		
		# create dbf file using the
		$create = @ dbase_create($db_file, $dbase_definition)
		   or die ("Could not create dbf file <i>$db_file</i>.");
		
		# open dbf file for reading and writing
		$id = @ dbase_open ($db_file, READ_WRITE)
		   or die ("Could not open dbf file <i>$db_file</i>."); 
		
		dbase_add_record ($id, $inari)
		   or die ("Could not add record 'inari' to dbf file <i>$db_file</i>."); 
		    
		dbase_add_record ($id, $unagi)
		   or die ("Could not add record 'unagi' to dbf file <i>$db_file</i>."); 
		
		# find the number of fields (columns) and rows in the dbf file
		$num_fields = dbase_numfields ($id);
		$num_rows   = dbase_numrecords($id);
		
		print "dbf file <i>$db_file</i> contains $num_rows row(s) with $num_fields field(s) in each row.\n";
		
		# Loop through the entries in the dbf file
		for ($i=1; $i <= $num_rows; $i++) {
		   print "\nRow $i of the database contains this information:<blockquote>";
		   print_r (dbase_get_record_with_names ($id,$i));
		   print "</blockquote>";
		} 
		
		# close the dbf file
		dbase_close($id);
	}
}