<?

$THE_BASE_PATH = '/mimik';

$THE_BASE_URL = 'http://redesign.ivygroup.com';

$THE_BASE_CDN_URL = 'http://c687583.r83.cf2.rackcdn.com';

$THE_CDN_CONTAINER_NAME = 'ivygroup';

$THE_BASE_SERVER_PATH = '/var/www/vhosts/ivygroup.com/subdomains/redesign';

$THE_ORGANIZATION_NAME = 'Ivy Group';

$THE_SITE_EMAIL_ADDRESS = 'mailer@ivygroup.com';

$THE_FILE_UPLOAD_TYPES = array('Image', 'File', 'Secure File', 'Video');

$THE_SECURE_FILES_PATH = 'mimik_secure_uploads';

define('THE_HOME_PAGE_VIEW_ID', 1);
define('THE_LANDING_PAGE_VIEW_ID', 2);
define('THE_INTERIOR_PAGE_VIEW_ID', 3);
define('THE_STANDALONE_PAGE_VIEW_ID', 4);
define('THE_HEADER_VIEW_ID', 5);
define('THE_FOOTER_VIEW_ID', 6);
define('THE_MENU_VIEW_ID', 7);
define('THE_MENU_ITEMS_VIEW_ID', 8);
define('THE_HOME_PAGE_SHOWCASE_ITEMS_VIEW_ID', 9);
define('THE_FEATURE_BOX_VIEW_ID', 10);
define('THE_JOB_OPENINGS_VIEW_ID', 11);
define('THE_INTERNS_VIEW_ID', 12);
define('THE_STAFF_VIEW_ID', 13);
define('THE_BLOG_POSTS_VIEW_ID', 14);
define('THE_BLOG_CATEGORY_LISTINGS_VIEW_ID', 15);
define('THE_BLOG_ARCHIVE_LISTINGS_VIEW_ID', 16);
define('THE_BLOG_MANAGEMENT_VIEW_ID', 17);
define('THE_PORTFOLIO_ITEMS_VIEW_ID', 18);
define('THE_CLIENTS_VIEW_ID', 19);
define('THE_BLOGROLL_LINKS_VIEW_ID', 25);

$THE_FIELD_TYPE_ARRAY = array(
							'Text' => array(
								'display_name' => 'Text',
								'value' => 'Text',
								'sql_field_type' => 'VARCHAR(255)' ),
							'Text Area' => array(
								'display_name' => 'Text Area',
								'value' => 'Text Area',
								'sql_field_type' => 'TEXT' ),
							'Number' => array(
								'display_name' => 'Number',
								'value' => 'Number',
								'sql_field_type' => 'INTEGER(11)'),
							'Decimal' => array(
								'display_name' => 'Decimal',
								'value' => 'Decimal',
								'sql_field_type' => 'FLOAT'),
							'Date' => array(
								'display_name' => 'Date',
								'value' => 'Date',
								'sql_field_type' => 'DATE' ),
							'Image' => array(
								'display_name' => 'Image',
								'value' => 'Image',
								'sql_field_type' => 'VARCHAR(255)' ),
							'File' => array(
								'display_name' => 'File',
								'value' => 'File',
								'sql_field_type' => 'VARCHAR(255)' ),
							'Secure File' => array(
								'display_name' => 'Secure File',
								'value' => 'Secure File',
								'sql_field_type' => 'VARCHAR(255)' ),
							'Video' => array(
								'display_name' => 'Video',
								'value' => 'Video',
								'sql_field_type' => 'VARCHAR(255)'),
							'Static Select' => array(
								'display_name' => 'Static Select',
								'value' => 'Static Select',
								'sql_field_type' => 'VARCHAR(255)' ),
							'Dynamic Select' => array(
								'display_name' => 'Dynamic Select',
								'value' => 'Dynamic Select',
								'sql_field_type' => 'INT(11)' ),
							'Static Radio' => array(
								'display_name' => 'Static Radio',
								'value' => 'Static Radio',
								'sql_field_type' => 'VARCHAR(255)' ),
							'Dynamic Radio' => array(
								'display_name' => 'Dynamic Radio',
								'value' => 'Dynamic Radio',
								'sql_field_type' => 'INT(11)' ),
							'WYSIWYG' => array(
								'display_name' => 'WYSIWYG',
								'value' => 'WYSIWYG',
								'sql_field_type' => 'TEXT' ),
							'Group Permission' => array(
								'display_name' => 'Group Permission',
								'value' => 'Group Permission',
								'sql_field_type' => NULL ),
							'User Permission' => array(
								'display_name' => 'User Permission',
								'value' => 'User Permission',
								'sql_field_type' => NULL ));
								
$THE_GENERIC_FIELDS = array(
							array(
								'id' => 0, 
								'display_name' => 'ID',
								'name' => 'id',
								'type' => 'Text',
								'display_in_management_view' => '0',
								'display_order_number' => '0',
								'table_id' => $The_Input_Table_ID,
								'is_generic' => 1 ),
							array(
								'id' => 0, 
								'display_name' => 'Create Date',
								'name' => 'create_date',
								'type' => 'Text',
								'display_in_management_view' => '0',
								'display_order_number' => '0',
								'table_id' => $The_Input_Table_ID,
								'is_generic' => 1 ),
							array(
								'id' => 0, 
								'display_name' => 'Modify Date',
								'name' => 'modify_date',
								'type' => 'Text',
								'display_in_management_view' => '0',
								'display_order_number' => '0',
								'table_id' => $The_Input_Table_ID,
								'is_generic' => 1 ),
							array(
								'id' => 0, 
								'display_name' => 'Created By',
								'name' => 'creator_user',
								'type' => 'Text',
								'display_in_management_view' => '0',
								'display_order_number' => '0',
								'table_id' => $The_Input_Table_ID,
								'is_generic' => 1 ),
							array(
								'id' => 0, 
								'display_name' => 'Last Modified By',
								'name' => 'modifier_user',
								'type' => 'Text',
								'display_in_management_view' => '0',
								'display_order_number' => '0',
								'table_id' => $The_Input_Table_ID,
								'is_generic' => 1 ) );

?>
