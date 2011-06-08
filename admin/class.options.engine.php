<?php

/**
 * 
 *
 *  Option Engine Class 
 *  Sorts and Draws options based on the 'option array'
 *  Option array is loaded in config.option.php and through filters
 *
 *  @package PageLines Core
 *  @subpackage Options
 *  @since 4.0
 *
 */

class PageLinesOptionEngine {

	function __construct() {
		
		$this->defaults = array(
			'default' 				=> '',
			'default_free'		 	=> null,
			'inputlabel' 			=> '',
			'type' 					=> 'check',
			'title' 				=> '',				
			'shortexp' 				=> '',
			'exp'					=> '',
			'wp_option'				=> false,
			'version' 				=> null,
			'version_set_default' 	=> 'free',
			'imagepreview' 			=> 200, 
			'selectvalues' 			=> array(),
			'fields'				=> array(),
			'optionicon' 			=> '', 
			'vidlink' 				=> null, 
			'vidtitle'				=> '',
			'docslink' 				=> null,
			'layout' 				=> 'normal', 
			'count_number' 			=> 10, 
			'selectors'				=> '', 
			'inputsize'				=> 'regular',
			'callback'				=> '',
			'css_prop'				=> '',
			'pro_note'				=> false
		);
		
		
	}

	/**
	 * Option generation engine
	 *
	 */
	function option_engine($oid, $o){

		$o = wp_parse_args( $o, $this->defaults );

		if($o['wp_option']) 
			$val = get_option($oid);
		else 
			$val = pagelines_option($oid);

		$draw_option = (!isset( $o['version'] ) || ( isset($o['version']) && $o['version'] == 'free') || (isset($o['version']) && $o['version'] == 'pro' && VPRO )) ? true : false;

		$layout_class = '';
		$layout_class .= ( isset( $o['layout'] ) && $o['layout']=='full' ) ? ' wideinputs' : '';
		$layout_class .= ( isset( $o['layout'] ) && $o['layout']=='interface' ) ? ' interface' : '';

	if( $draw_option ):  ?>
	<div class="optionrow fix <?php echo $layout_class;?>">
			<?php if( $o['title'] ): ?>
			<div class="optiontitle fix">
				<div class="optiontitle-pad fix">

					<?php if( isset($o['vidlink']) ):?>
						<a class="vidlink thickbox" title="<?php if($o['vidtitle']) echo $o['vidtitle']; ?>" href="<?php echo $o['vidlink']; ?>?hd=1&KeepThis=true&TB_iframe=true&height=450&width=700">
							<img src="<?php echo PL_ADMIN_IMAGES . '/link-video.jpg';?>" class="docslink-video" alt="Video Tutorial" />
						</a>
					<?php endif;?>

					<?php if( isset($o['docslink']) ):?>
						<a class="vidlink" title="<?php if($o['vidtitle']) echo $o['vidtitle']; ?>" href="<?php echo $o['docslink']; ?>" target="_blank">
							<img src="<?php echo PL_ADMIN_IMAGES . '/link-docs.jpg';?>" class="docslink-video" alt="Video Tutorial" />
						</a>
					<?php endif;?>

					<strong><?php echo $o['title'];?></strong><br/>
					<small><?php echo $o['shortexp'];?></small><br/>
				</div>
			</div>
			<?php endif;?>
			<div class="theinputs ">
				<div class="optioninputs">
					<?php $this->option_breaker($oid, $o, $val); ?>
				</div>
			</div>

			<?php if($o['exp'] && $o['type'] != 'text_content'):?>
			<div class="theexplanation">
				<div class="theexplanation-pad">
					<div class="context">More Info</div>
					<p><?php echo $o['exp'];?></p>
					<?php if( $o['pro_note'] && !VPRO ): ?>
						<p class="pro_note"><strong>Pro Version Note:</strong><br/><?php echo $o['pro_note']; ?></p>
					<?php endif; ?>
				</div>
			</div>
			<?php endif;?>
	<div class="clear"></div>
	</div>
<?php endif; 
	}
	

	/**
	 * 
	 * Option Breaker 
	 * Switches through an option array, generating the option handling and markup
	 *
	 */
	function option_breaker($oid, $o, $val = ''){

		switch ( $o['type'] ){

			case 'select' :
				$this->_get_select_option($oid, $o);
				break;
			case 'select_same' :
				$this->_get_select_option($oid, $o);
				break;
			case 'radio' :
				$this->_get_radio_option($oid, $o);
				break;
			case 'colorpicker' :
				$this->_get_color_picker($oid, $o);
				break;
			case 'color_multi' :
				$this->_get_color_multi($oid, $o);
				break;
			case 'count_select' :
				$this->_get_count_select_option($oid, $o);
				break;
			case 'select_taxonomy' :
				$this->_get_taxonomy_select($oid, $o);
				break;
			case 'textarea' :
				$this->_get_textarea($oid, $o, $val);
				break;
			case 'textarea_big' :
				$this->_get_textarea($oid, $o, $val);
				break;
			case 'text' :
				$this->_get_text($oid, $o, $val);
				break;
			case 'text_small' :
				$this->_get_text_small($oid, $o, $val);
				break;
			case 'css_option' :
				$this->_get_text_small($oid, $o, $val);
				break;
			case 'text_multi' :
				$this->_get_text_multi($oid, $o, $val);
				break;
			case 'check' :
				$this->_get_check_option($oid, $o);
				break;
			case 'check_multi' :
				$this->_get_check_multi($oid, $o, $val);
				break;

			case 'typography' :
				$this->_get_typography_option($oid, $o, $val);
				break;
			case 'select_menu' :
				$this->_get_menu_select($oid, $o);
				break;
			case 'image_upload' :
				$this->_get_image_upload_option($oid, $o, $val);
				break;
			case 'background_image' :
				$this->_get_background_image_control($oid, $o); 
				break;
			case 'layout' :
				$this->_get_layout_builder($oid, $o);
				break;
			case 'layout_select' :
				$this->_get_layout_select($oid, $o); 
				break;
			case 'templates' :
				$this->do_template_builder(); 
				break;
			case 'text_content' :
				$this->_get_text_content($oid, $o, $val);
				break;
			case 'reset' :
				$this->_get_reset_option($oid, $o, $val);
				break;

			default :
				do_action( 'pagelines_options_' . $o['type'] , $oid, $o);
				break;

		} 

	}

	function _get_menu_select($oid, $o){ ?>
		<p>
			<label for="<?php pagelines_option_id($oid); ?>" class="context"><?php echo $o['inputlabel'];?></label><br/>
			<select id="<?php pagelines_option_id($oid); ?>" name="<?php pagelines_option_name($oid); ?>">
				<option value="">&mdash;SELECT&mdash;</option>
				<?php	$menus = wp_get_nav_menus( array('orderby' => 'name') );
						foreach ( $menus as $menu )
							printf( '<option value="%d" %s>%s</option>', $menu->term_id, selected($menu->term_id, pagelines_option($oid)), esc_html( $menu->name ) );
				?>
			</select>
		</p>

	<?php }

	function _get_typography_option($oid, $o, $val){

		global $pl_foundry; 

		$fonts = $pl_foundry->foundry; 

		$preview_styles = '';

		$preview_styles = $pl_foundry->get_type_css(pagelines_option($oid));

		// Choose Font
		?>
		<label for="<?php pagelines_option_id($oid, 'font'); ?>" class="context">Select Font</label><br/>
		<select id="<?php pagelines_option_id($oid, 'font'); ?>" name="<?php pagelines_option_name($oid, 'font'); ?>" onChange="PageLinesStyleFont(this, 'font-family')" class="fontselector" size="1" >
			<option value="">&mdash;SELECT&mdash;</option>
			<?php foreach($fonts as $fid => $f):

				if(!VPRO && !$f['free']):

				else: 
					$font_name = $f['name']; 

					if($f['web_safe']) $font_name .= ' *';
					if($f['google']) $font_name .= ' G';

			?>
				<option value='<?php echo $fid;?>' id='<?php echo $f['family'];?>' title="<?php echo $pl_foundry->gfont_key($fid);?>" <?php selected( $fid, pagelines_sub_option($oid, 'font') ); ?>><?php echo $font_name;?></option>
			<?php endif; endforeach;?>
		</select>
		<div class="font_preview_wrap">
			<label class="context">Preview</label>
			<div class="font_preview" >
				<div class="font_preview_pad" style='<?php echo $preview_styles;?>' >
					The quick brown fox jumps over the lazy dog.
				</div>
			</div>
		</div>
		<span id="<?php pagelines_option_id($oid, '_set_styling_button'); ?>" class="button" onClick="PageLinesSimpleToggle('#<?php pagelines_option_id($oid, '_set_styling'); ?>', '#<?php pagelines_option_id($oid, '_set_advanced'); ?>')">Edit Font Styling</span>

		<span id="<?php pagelines_option_id($oid, '_set_advanced_button'); ?>" class="button" onClick="PageLinesSimpleToggle('#<?php pagelines_option_id($oid, '_set_advanced'); ?>', '#<?php pagelines_option_id($oid, '_set_styling'); ?>')">Advanced</span>

		<div id="<?php pagelines_option_id($oid, '_set_styling'); ?>" class="font_styling type_inputs">
			<?php $this->get_type_styles($oid, $o); ?>
			<div class="clear"></div>
		</div>

		<div id="<?php pagelines_option_id($oid, '_set_advanced'); ?>" class="advanced_type type_inputs">
			<?php $this->get_type_advanced($oid, $o); ?>
			<div class="clear"></div>
		</div>


	<?php }

	function get_type_styles($oid, $o){

		// Set Letter Spacing (em)
		$this->_get_type_em_select($oid, array());

		// Convert to caps, small-caps?
		$this->_get_type_select($oid, array('id' => 'transform', 'inputlabel' => 'Text Transform', 'prop' => 'text-transform',  'selectvalues' => array('none' => 'None', 'uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'lowercase'), 'default' => 'none'));

		// Small Caps?
		$this->_get_type_select($oid, array('id' => 'variant', 'inputlabel' => 'Variant', 'prop' => 'font-variant',  'selectvalues' => array('normal' => 'Normal', 'small-caps' => 'Small-Caps'), 'default' => 'normal'));

		// Bold? 
		$this->_get_type_select($oid, array('id' => 'weight', 'inputlabel' => 'Weight', 'prop' => 'font-weight', 'selectvalues' => array('normal' => 'Normal', 'bold' => 'Bold'), 'default' => 'normal'));
		// 
		// Italic?
		$this->_get_type_select($oid, array('id' => 'style', 'inputlabel' => 'Style', 'prop' => 'font-style',  'selectvalues' => array('normal' => 'Normal', 'italic' => 'Italic'), 'default' => 'normal'));
	}

	function get_type_advanced($oid, $o){ ?>
		<div class="type_advanced">
			<label for="<?php pagelines_option_id($oid, 'selectors'); ?>" class="context">Additional Selectors</label><br/>
			<textarea class=""  name="<?php pagelines_option_name($oid, 'selectors'); ?>" id="<?php pagelines_option_id($oid, 'selectors'); ?>" rows="3"><?php esc_attr_e( pagelines_sub_option($oid, 'selectors'), 'pagelines' ); ?></textarea>
		</div>
	<?php }

	function _get_type_em_select($oid, $o){ 

		$option_value = ( pagelines_sub_option($oid, 'kern') ) ? pagelines_sub_option($oid, 'kern') : '0.00em';
		?>
		<div class="type_select">
		<label for="<?php pagelines_option_id($oid, 'kern'); ?>" class="context">Letter Spacing</label><br/>
		<select id="<?php pagelines_option_id($oid, 'kern'); ?>" name="<?php pagelines_option_name($oid, 'kern'); ?>" onChange="PageLinesStyleFont(this, 'letter-spacing')">
			<option value="">&mdash;SELECT&mdash;</option>
			<?php 
				$count_start = -.3;
				for($i = $count_start; $i <= 1; $i += 0.05):
					$em = number_format(round($i, 2), 2).'em';
			?>
					<option value="<?php echo $em;?>" <?php selected($em, $option_value); ?>><?php echo $em;?></option>
			<?php endfor;?>
		</select>
		</div>
	<?php }

	function _get_type_select($oid, $o){ 

		$option_value = ( pagelines_sub_option($oid, $o['id']) ) ? pagelines_sub_option($oid, $o['id']) : $o['default'];
		?>
		<div class="type_select">
			<label for="<?php pagelines_option_id($oid, $o['id']); ?>" class="context"><?php echo $o['inputlabel'];?></label><br/>
			<select id="<?php pagelines_option_id($oid, $o['id']); ?>" name="<?php pagelines_option_name($oid, $o['id']); ?>" onChange="PageLinesStyleFont(this, '<?php echo $o['prop'];?>')">
				<option value="">&mdash;SELECT&mdash;</option>
				<?php foreach($o['selectvalues'] as $sid => $s):?>
						<option value="<?php echo $sid;?>" <?php selected($sid, $option_value); ?>><?php echo $s;?></option>
				<?php endforeach;?>
			</select>
		</div>
	<?php }	

	function _get_check_option($oid, $o){ ?>
		<p>
			<label for="<?php pagelines_option_id($oid); ?>" class="context">
				<input class="admin_checkbox" type="checkbox" id="<?php pagelines_option_id($oid); ?>" name="<?php pagelines_option_name($oid); ?>" <?php checked((bool) pagelines_option($oid)); ?> />
				<?php echo $o['inputlabel'];?>
			</label>
		</p>
	<?php }	

	function _get_check_multi($oid, $o, $val){ 
		foreach($o['selectvalues'] as $mid => $mo):?>
		<p>
			<label for="<?php echo $mid;?>" class="context"><input class="admin_checkbox" type="checkbox" id="<?php echo $mid;?>" name="<?php pagelines_option_name($mid); ?>" <?php checked((bool) pagelines_option($mid)); ?>  /><?php echo $mo['inputlabel'];?></label>
		</p>
	<?php endforeach; 
	}

	function _get_text_multi($oid, $o, $val){ 
		foreach($o['selectvalues'] as $mid => $m):?>
		<p>
			<label for="<?php echo $mid;?>" class="context"><?php echo $m['inputlabel'];?></label><br/>
			<input class="<?php echo $o['inputsize'];?>-text" <?php echo ( strpos( $mid, 'password' ) ) ? 'type="password"' : 'type="text"'; ?> id="<?php echo $mid;?>" name="<?php pagelines_option_name($mid); ?>" value="<?php echo esc_attr( pagelines_option($mid) ); ?>"  />
		</p>
		<?php endforeach;
	}

	function _get_text_small($oid, $o, $val){ ?>
		<p>
			<label for="<?php echo $oid;?>" class="context"><?php echo $o['inputlabel'];?></label><br/>
			<input class="small-text"  type="text" name="<?php pagelines_option_name($oid); ?>" id="<?php echo $oid;?>" value="<?php pl_ehtml( pagelines_option($oid) ); ?>" />
		</p>
	<?php }

	function _get_text($oid, $o, $val){ 

		global $pl_data;

		?>
		<p>
			<label for="<?php echo $oid;?>" class="context"><?php echo $o['inputlabel'];?></label>
			<input class="regular-text"  type="text" name="<?php pagelines_option_name($oid); ?>" id="<?php echo $oid;?>" value="<?php pl_ehtml( pagelines_option($oid) ); ?>" />
		</p>
	<?php }

	function _get_textarea($oid, $o, $val){ ?>
		<p>
			<label for="<?php echo $oid;?>" class="context"><?php echo $o['inputlabel'];?></label><br/>
			<textarea name="<?php pagelines_option_name($oid); ?>" class="html-textarea <?php if($o['type']=='textarea_big') echo "longtext";?>" cols="70%" rows="5"><?php pl_ehtml( pagelines_option($oid) ); ?></textarea>
		</p>
	<?php }


	function _get_text_content($oid, $o, $val){ ?>
		<div class="text_content fix"><?php echo $o['exp'];?></div>
	<?php }

	function _get_reset_option($oid, $o, $val){ 

		pl_action_confirm('Confirm'.$oid, 'Are you sure?');

	?>
		<div class="insidebox context">
			<input class="button-secondary reset-options" type="submit" name="<?php pagelines_option_name($oid); ?>" onClick="return Confirm<?php echo $oid;?>();" value="<?php echo $o['inputlabel'];?>" /> <?php echo $o['exp'];?>
		</div>
	<?php 

	}


	function _get_image_upload_option( $oid, $o, $optionvalue = ''){ 

		?><p>	
			<label class="context" for="<?php echo $oid;?>"><?php echo $o['inputlabel'];?></label><br/>
			<input class="regular-text uploaded_url" type="text" name="<?php pagelines_option_name($oid); ?>" value="<?php echo esc_url(pagelines_option($oid));?>" /><br/><br/>
			<span id="<?php echo $oid; ?>" class="image_upload_button button">Upload Image</span>
			<span title="<?php echo $oid;?>" id="reset_<?php echo $oid; ?>" class="image_reset_button button">Remove</span>
			<input type="hidden" class="ajax_action_url" name="wp_ajax_action_url" value="<?php echo admin_url("admin-ajax.php"); ?>" />
			<input type="hidden" class="image_preview_size" name="img_size_<?php echo $oid;?>" value="<?php echo $o['imagepreview'];?>"/>
		</p>
		<?php if(pagelines_option($oid)):?>
			<img class="pagelines_image_preview" id="image_<?php echo $oid;?>" src="<?php echo pagelines_option($oid);?>" style="max-width:<?php echo $o['imagepreview'];?>px"/>
		<?php endif;?>

	<?php }

	function _get_count_select_option( $oid, $o, $optionvalue = '' ){ ?>

			<p>
				<label for="<?php echo $oid;?>" class="context"><?php echo $o['inputlabel'];?></label><br/>
				<select id="<?php echo $oid;?>" name="<?php pagelines_option_name($oid); ?>">
					<option value="">&mdash;SELECT&mdash;</option>
					<?php if(isset($o['count_start'])): $count_start = $o['count_start']; else: $count_start = 0; endif;?>
					<?php for($i = $count_start; $i <= $o['count_number']; $i++):?>
							<option value="<?php echo $i;?>" <?php selected($i, pagelines_option($oid)); ?>><?php echo $i;?></option>
					<?php endfor;?>
				</select>
			</p>

	<?php }

	function _get_radio_option( $oid, $o ){ ?>

			<?php foreach($o['selectvalues'] as $selectid => $selecttext):?>
				<p>
					<input type="radio" id="<?php echo $oid;?>_<?php echo $selectid;?>" name="<?php pagelines_option_name($oid); ?>" value="<?php echo $selectid;?>" <?php checked($selectid, pagelines_option($oid)); ?>> 
					<label for="<?php echo $oid;?>_<?php echo $selectid;?>"><?php echo $selecttext;?></label>
				</p>
			<?php endforeach;?>

	<?php }

	function _get_select_option( $oid, $o ){ ?>

			<p>
				<label for="<?php echo $oid;?>" class="context"><?php echo $o['inputlabel'];?></label><br/>
				<select id="<?php echo $oid;?>" name="<?php pagelines_option_name($oid); ?>">
					<option value="">&mdash;SELECT&mdash;</option>

					<?php foreach($o['selectvalues'] as $sval => $select_set):?>
						<?php if($o['type'] == 'select_same'):?>
								<option value="<?php echo $select_set;?>" <?php selected($select_set, pagelines_option($oid)); ?>><?php echo $select_set;?></option>
						<?php else:?>
								<option value="<?php echo $sval;?>" <?php selected($sval, pagelines_option($oid)); ?>><?php echo $select_set['name'];?></option>
						<?php endif;?>

					<?php endforeach;?>
				</select>
			</p>
	<?php }

	function _get_taxonomy_select( $oid, $o ){ 
		$terms_array = get_terms( $o['taxonomy_id']); 

		if(is_array($terms_array) && !empty($terms_array)):	?>
			<label for="<?php echo $oid;?>" class="context"><?php echo $o['inputlabel'];?></label><br/>
			<select id="<?php echo $oid;?>" name="<?php pagelines_option_name($oid); ?>">
				<option value="">&mdash;<?php _e("SELECT", 'pagelines');?>&mdash;</option>
				<?php foreach($terms_array as $term):?>
					<option value="<?php echo $term->slug;?>" <?php if( pagelines_option($oid) == $term->slug ) echo 'selected';?>><?php echo $term->name; ?></option>
				<?php endforeach;?>
			</select>
	<?php else:?>
			<div class="meta-message"><?php _e('No sets have been created and added to a post yet!', 'pagelines');?></div>
	<?php endif;

	}

	function _get_color_multi($oid, $o){ 	

		foreach($o['selectvalues'] as $mid => $m):

			if( !isset($m['version']) || (isset($m['version']) && $m['version'] != 'pro') || (isset($m['version']) && $m['version'] == 'pro' && VPRO )):
				$this->_get_color_picker($mid, $m);
			endif;

		endforeach; 

	}


	function _get_color_picker($oid, $o){ // Color Picker Template 
		?>

		<div class="the_picker">
			<label for="<?php echo $oid;?>" class="colorpicker_label context"><?php echo $o['inputlabel'];?></label>
			<div id="<?php echo $oid;?>_picker" class="colorSelector"><div></div></div>
			<input class="colorpickerclass"  type="text" name="<?php pagelines_option_name($oid); ?>" id="<?php echo $oid;?>" value="<?php echo pagelines_option($oid); ?>" />
		</div>
	<?php  }

	function _get_background_image_control($oid, $option_settings){

		$bg_fields = $this->_background_image_array();

		$this->_get_image_upload_option($oid.'_url', $bg_fields['_url'], pagelines_option($oid.'_url'));
		$this->_get_select_option($oid.'_repeat', $bg_fields['_repeat']);
		$this->_get_count_select_option( $oid.'_pos_vert', $bg_fields['_pos_vert']);
		$this->_get_count_select_option( $oid.'_pos_hor', $bg_fields['_pos_hor']);

	}


	function _background_image_array(){
		return array(
			'_url' => array(		
					'inputlabel' 	=> 'Background Image',
					'imagepreview'	=> 150
			),
			'_repeat' => array(			
					'inputlabel'	=> 'Set Background Image Repeat',
					'type'			=> 'select',
					'selectvalues'	=> array(
						'no-repeat'	=> array('name' => 'Do Not Repeat'), 
						'repeat'	=> array('name' => 'Tile'), 
						'repeat-x'	=> array('name' => 'Repeat Horizontally'), 
						'repeat-y'	=> array('name' => 'Repeat Vertically')
					)
			),
			'_pos_vert' => array(				
					'inputlabel'	=> 'Vertical Position In Percent',
					'type'			=> 'count_select',
					'count_start'	=> 0, 
					'count_number'	=> 100,
			),
			'_pos_hor' => array(				
					'inputlabel'	=> 'Horizontal Position In Percent',
					'type'			=> 'count_select',
					'count_start'	=> 0, 
					'count_number'	=> 100,
			),

		);
	}


			/**
			 * 
			 *
			 *  Layout Builder (Layout Drag & Drop)
			 *
			 *
			 *  @package PageLines Core
			 *  @subpackage Options
			 *  @since 4.0
			 *
			 */
			function _get_layout_builder($optionid, $option_settings){ ?>
				<div class="layout_controls selected_template">


					<div id="layout-dimensions" class="template-edit-panel">
						<h3>Configure Layout Dimensions</h3>
						<div class="select-edit-layout">
							<div class="layout-selections layout-builder-select fix">
								<div class="layout-overview">Select Layout To Edit</div>
								<?php


								global $pagelines_layout;
								foreach(get_the_layouts() as $layout):

									$the_last_edited = (pagelines_sub_option('layout', 'last_edit')) ? pagelines_sub_option('layout', 'last_edit') : 'one-sidebar-right';

									$load_layout = ($the_last_edited == $layout) ? true : false;

								?>
								<div class="layout-select-item">
									<span class="layout-image-border <?php if($load_layout) echo 'selectedlayout';?>">
										<span class="layout-image <?php echo $layout;?>">&nbsp;</span>
									</span>
									<input type="radio" class="layoutinput" name="<?php pagelines_option_name('layout', 'last_edit'); ?>" value="<?php echo $layout;?>" <?php if($load_layout) echo 'checked';?> />
								</div>
								<?php endforeach;?>

							</div>	
						</div>
						<?php

					foreach(get_the_layouts() as $layout):

					$buildlayout = new PageLinesLayout($layout);
						?>
					<div class="layouteditor <?php echo $layout;?> <?php if($buildlayout->layout_map['last_edit'] == $layout) echo 'selectededitor';?>">
							<div class="layout-main-content" style="width:<?php echo $buildlayout->builder->bwidth;?>px">

								<div id="innerlayout" class="layout-inner-content" >
									<?php if($buildlayout->west->id != 'hidden'):?>
									<div id="<?php echo $buildlayout->west->id;?>" class="ui-layout-west innerwest loelement locontent"  style="width:<?php echo $buildlayout->west->bwidth;?>px">
										<div class="loelement-pad">
											<div class="loelement-info">
												<div class="layout_text"><?php echo $buildlayout->west->text;?></div>
												<div class="width "><span><?php echo $buildlayout->west->width;?></span>px</div>
											</div>
										</div>
									</div>
									<?php endif;?>
									<div id="<?php echo $buildlayout->center->id;?>" class="ui-layout-center loelement locontent innercenter">
										<div class="loelement-pad">
											<div class="loelement-info">
												<div class="layout_text"><?php echo $buildlayout->center->text;?></div>
												<div class="width "><span><?php echo $buildlayout->center->width;?></span>px</div>
											</div>
										</div>
									</div>
									<?php if( $buildlayout->east->id != 'hidden'):?>
									<div id="<?php echo $buildlayout->east->id;?>" class="ui-layout-east innereast loelement locontent" style="width:<?php echo $buildlayout->east->bwidth;?>px">
										<div class="loelement-pad">
											<div class="loelement-info">
												<div class="layout_text"><?php echo $buildlayout->east->text;?></div>
												<div class="width "><span><?php echo $buildlayout->east->width;?></span>px</div>
											</div>
										</div>
									</div>
									<?php endif;?>
									<div id="contentwidth" class="ui-layout-south loelement locontent" style="background: #fff;">
										<div class="loelement-pad"><div class="loelement-info"><div class="width"><span><?php echo $buildlayout->content->width;?></span>px</div></div></div>
									</div>
									<div id="top" class="ui-layout-north loelement locontent"><div class="loelement-pad"><div class="loelement-info">Content Area</div></div></div>
								</div>
								<div class="margin-west loelement"><div class="loelement-pad"><div class="loelement-info">Margin<div class="width"></div></div></div></div>
								<div class="margin-east loelement"><div class="loelement-pad"><div class="loelement-info">Margin<div class="width"></div></div></div></div>

							</div>


								<div class="layoutinputs">
									<label class="context" for="input-content-width">Global Content Width</label>
									<input type="text" name="<?php pagelines_option_name('layout', 'content_width'); ?>" id="input-content-width" value="<?php echo $buildlayout->content->width;?>" size=5 readonly/>
									<label class="context"  for="input-maincolumn-width">Main Column Width</label>
									<input type="text" name="<?php pagelines_option_name('layout', $layout, 'maincolumn_width'); ?>" id="input-maincolumn-width" value="<?php echo $buildlayout->main_content->width;?>" size=5 readonly/>

									<label class="context"  for="input-primarysidebar-width">Sidebar1 Width</label>
									<input type="text" name="<?php pagelines_option_name('layout', $layout, 'primarysidebar_width'); ?>" id="input-primarysidebar-width" value="<?php echo  $buildlayout->sidebar1->width;?>" size=5 readonly/>
								</div>
					</div>
					<?php endforeach;?>

				</div>
			</div>
			<?php }

	/**
	 * 
	 *
	 *  Layout Select (Layout Selector)
	 *
	 *
	 *  @package PageLines Core
	 *  @subpackage Options
	 *  @since 4.0
	 *
	 */
	function _get_layout_select($optionid, $option_settings){ ?>
		<div id="layout_selector" class="template-edit-panel">

			<div class="layout-selections layout-select-default fix">
				<div class="layout-overview">Default Layout</div>
				<?php


				global $pagelines_layout;
				foreach(get_the_layouts() as $layout):
				?>
				<div class="layout-select-item">
					<span class="layout-image-border <?php if($pagelines_layout->layout_map['saved_layout'] == $layout) echo 'selectedlayout';?>"><span class="layout-image <?php echo $layout;?>">&nbsp;</span></span>
					<input type="radio" class="layoutinput" name="<?php pagelines_option_name('layout', 'saved_layout'); ?>" value="<?php echo $layout;?>" <?php if($pagelines_layout->layout_map['saved_layout'] == $layout) echo 'checked';?>>
				</div>
				<?php endforeach;?>

			</div>

		</div>
		<div class="clear"></div>
	<?php }

	function do_template_builder(){

		$builder = new PageLinesTemplateBuilder();
		$builder->draw_template_builder();

	}

} // End of Class