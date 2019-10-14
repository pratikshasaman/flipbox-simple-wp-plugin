<?php
/*
Plugin Name: Flipbox
Description: Flipbox plugin
Version : 1.0.0
Author: Pratiksha Samane
*/

function aw_include_script123() {
 
    if ( ! did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }
  
    wp_enqueue_script( 'awscript', plugin_dir_url( __FILE__ ) . 'script.js', array('jquery'), null, false);
}
add_action( 'admin_enqueue_scripts', 'aw_include_script123' );

function load_style() {
    wp_register_style( 'style',  plugin_dir_url( __FILE__ ) . 'style.css' );
    wp_enqueue_style('style');
}
add_action( 'wp_enqueue_scripts', 'load_style' );


add_action("init","flipbox_function");
function flipbox_function(){
		$labels=array(
		'name'=>__('Custom Flipbox'),
		'singular_name'=>__('obj1'), //menu setting
		'menu_name'=>__('Flipbox'), // menu name
		'name_admin_bar'=>__('Flipbox'), //+
		'add_new'=>__('Add New'),
		'add_new_item'=>__('Add New Flipbox'),
		'new_item'=>__('New Flipbox'),
		'edit_item'=>__('Edit Flipbox'), // at run time in admin bar
		'view_item'=>__('View Flipbox'), //at admin bar
		'all_items'=>__('All Flipbox'), 
		'search_items'=>__('Search Flipbox'),
		'parent_item_colon'=>__('Parent Flipbox:'),
		'not_found'=>__('No Flipbox Found'),
		'not_found_in_trash'=>__('No Flipbox Found in Trash')
	);

		$args = array(
		'labels'=>$labels,
		'public'=>true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'menu_position'		=> 20, //menu position
		'rewrite'            => array( 'slug' => 'flipbox' ),
		'capability_type'    => 'post', // 
		'can_export'		=> true, // tools->export
		'show_in_rest'		=> true, //Gutternberg Editor
		'supports'           => array( 'title')
	);
		register_post_type('flipbox_reg',$args);
}


//add meta box
function meta_box_fu12(){
	add_meta_box("meta_box","Flipbox Setting","m_fun12","flipbox_reg","side","high");
}
add_action("add_meta_boxes_flipbox_reg","meta_box_fu12");
function m_fun12($post){
	?>
	<div>
		<div>
			<label><strong>Upload Image:</strong></label><br><br>
			<input type="text" name="text_box" value="<?php echo get_post_meta($post->ID,"img1",true);?>" id="disp"><br><br>
			<input type="submit" name="upload_img" value="Choose Image" id="upload_image">
		</div>
		<br><br>
		<div>
			<label><strong>Select Effect:</strong></label>

			<select name="select_option">
				<option>Top to bottom</option>
				<option>Left to right</option>
			</select>
		</div>
		<br><br>
		<div>
			<label><strong>Enter Text:</strong></label><br><br>
			<textarea rows="5" cols="30" name="description"><?php echo get_post_meta($post->ID,"desp",true);?></textarea>
		</div>
	</div>
	<?php
}

add_action("save_post","save_function");
function save_function($post_id){
	$val= isset($_REQUEST['description']) ? trim($_REQUEST['description']) : "";
	$val2= isset($_REQUEST['select_option']) ? trim($_REQUEST['select_option']) : "";
	$val3= isset($_REQUEST['text_box']) ? trim($_REQUEST['text_box']) : "";
	$val4= isset($_REQUEST['shortcode']) ? trim($_REQUEST['shortcode']) : "";
	// var_dump($val4);die();
	if (!empty($val) && !empty($val2) && !empty($val3)) {
		$arr=array( $val,$val2,$val3,$val4);
update_post_meta($post_id, "post_meta_val",$arr,true);
        // update_post_meta($post_id, "img1", $val3,true);
        // update_post_meta($post_id, "desp", $val,true);
        // update_post_meta($post_id, "select_opt",$val2,true);
    }
}

function flipbox_shortcode_fun($atts){
	$a=get_post_meta($atts['id'],'img1',true);
	// var_dump($a);
	$b=get_post_meta($atts['id'],'desp',true);
	$c=get_post_meta($atts['id'],'select_opt',true);
	// var_dump(array($a,$b,$c));	
	$flip='';
	if($c=='Left to right')
	{
		$flip='horizontal';
	}
	else{
		$flip='vertical';
	}
	?>
<div class="flip-box" id="flip-id">
          <div class="flip-box-inner <?php echo( $flip ); ?>" id="inner-id">
            <div class="flip-box-front">
              <img src="<?php echo $a; ?>" alt="Smiley face" />
            </div>
            <div class="flip-box-back" id="inner-back">
              <div>
                  <?php echo $b; ?>
              </div>
            </div>
          </div>
        </div>
	<?php
}
add_shortcode("flipAnything","flipbox_shortcode_fun");

function meta_box_function(){
	add_meta_box("metaBox","Shortcode","meta_fun","flipbox_reg","side","high");
}
add_action("add_meta_boxes","meta_box_function");
function meta_fun(){
?>
<strong>Shortcode:</strong><input type="text" name="shortcode" value='<?php echo "[flipAnything id=".get_the_id()." title=".get_the_title()."]"?>'>
<?php
}

 function addcolumn($columns)
{
    $columns = array(
        "cb"=>"<input type='checkbox'/)",
        "title"=>"Flipbox Title",
        "shrtcd"=>"Shortcode",
        "date"=>"Date"
    );
    return $columns;
}
add_action("manage_flipbox_reg_posts_columns","addcolumn");



function render_data1($column,$post_id)
{
	$z=get_the_id();
	$p=get_the_title();
	echo "[flipAnything id=".$z." title=".$p."]";
}
add_action("manage_flipbox_reg_posts_custom_column","render_data1",10,2);
?>