<?php
/*
Plugin Name: Custom Flipbox
Description: Flipbox plugin
Version : 1.0.0
Author: Pratiksha Samane
*/
echo "hello World!!1";
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
	add_meta_box("meta_box","Flipbox Setting","m_fun12","flipbox_reg","normal","high");
}
add_action("add_meta_boxes_flipbox_reg","meta_box_fu12");
function m_fun12($post){
	?>
	<table class="form-table" role="presentation">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row">
					<span class="dashicons dashicons-format-image"></span>
					<label>Upload Image:</label>
				</th>
				<td>
					<?php $output_img = get_post_meta($post->ID,"post_meta_val",true);
					// var_dump($output_img);die(); 
              		$img = isset($output_img['img1']) ? $output_img['img1'] : '';
              		$description = isset($output_img['desp']) ? $output_img['desp'] : '';
					?>
					<input type="text" name="text_box" id="disp" value="<?php echo  $img; ?>">
					<p class="description"></p>
				</td>
				<td>
					<input type="submit" name="upload_img" value="Choose Image" class="button button-primary button-large" id="upload_image">
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row">
					<span class="dashicons dashicons-image-flip-horizontal"></span>
					<label>Flip Type:</label>
				</th>
				<td>
					<select name="select_option" style="min-width: 50%;">
						<option>Top to bottom</option>
						<option>Left to right</option>
					</select>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row">
					<span class="dashicons dashicons-star-filled"></span>
					<label>BackEnd Text:</label>
				</th>
				<td>
					<textarea rows="5" cols="30" name="description"><?php echo $description;?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

add_action("save_post","save_function");
function save_function($post_id){
	$Description= isset($_REQUEST['description']) ? trim($_REQUEST['description']) : "";
	$select_option= isset($_REQUEST['select_option']) ? trim($_REQUEST['select_option']) : "";
	$image_url= isset($_REQUEST['text_box']) ? trim($_REQUEST['text_box']) : "";
	$shortcode= isset($_REQUEST['shortcode']) ? trim($_REQUEST['shortcode']) : "";
	if (!empty($Description) && !empty($select_option) && !empty($image_url) && !empty($shortcode)) {	
		
		$defaults = array(
                    // General Settings.
                        'img1'     =>  $image_url,
                        'desp'   => $Description,
                        'select_opt' =>$select_option,
                        'shortcode'=>$shortcode,
                    );
		update_post_meta($post_id, "post_meta_val",$defaults,true);
    }
}

function flipbox_shortcode_fun($atts){
	$output_img = get_post_meta($atts['id'],"post_meta_val",true);
	$a=$output_img['img1'];
	$b=$output_img['desp'];
	$c=$output_img['select_opt'];
	
	$flip='';
	if($c=='Left to right')
	{
		$flip='horizontal';
	}
	else{
		$flip='vertical';
	}
	?>
		<div class="card1 flip-box">
			<div class="flip-box-inner <?php echo( $flip ); ?>">
				<div class="front">
					<div class="step1">
						<!-- <div class="step2"> -->
							<img src="<?php echo $a; ?>"/>
						<!-- </div> -->
					</div>					
				</div>	
				<div class="back"<?php echo( $flip == 'horizontal') ? "style='transform: rotateY(180deg);'" : "style='transform: rotateX(180deg);'";?>>
					<div class="back-content">
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
	<em><strong>Shortcode for posts/pages/plugins</strong></em>
	<p>Copy & paste the shortcode directly into any WordPress post or page.</p>
<input type="text" name="shortcode" value="<?php echo "[flipAnything id=".get_the_id()." title=".get_the_title()."]";?>" style="width: 250px;">
 
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