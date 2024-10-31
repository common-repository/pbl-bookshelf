<?php 
	/*
		Plugin Name: PBL Bookself
		Plugin URI: http://purebyteslab.com/blog/bookshelf-wordpress-widget/
		Description: Adds a widget to display a book with a title next to it
		Version: 1.0.0
		Author: Pure Bytes Lab
		Author URI: http://www.purebyteslab.com/
		License: GPL
		
		This software comes without any warranty, express or otherwise, and if it
		breaks your blog or results in your cat being shaved, it's not my fault.
		
	*/
	
	
	
	class pblBookshelf extends WP_Widget
	
	{
		
		function pblBookshelf(){
			
			$widget_ops = array('description' => 'A widget that shows a preview of a book');
			
			$control_ops = array('width' => 400, 'height' => 300);
			
			parent::WP_Widget(false,$name='PBL Bookshelf',$widget_ops,$control_ops);
			
		}
		
		
		
		/* Displays the Widget in the front-end */
		
		function widget($args, $instance){
			
			extract( $args );
			
			function my_script() {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui','https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js' );
				wp_enqueue_script('custom_script',plugin_dir_url(__FILE__).'/pblBookshelf/script.js');
			}
			add_action('scripts', 'my_script');
			do_action('scripts');
			/* Our variables from the widget settings. */
			$title = apply_filters('PBL Bookshelf', $instance['title'] );
			
			$cats ='';
			$categories=  get_categories('hide_empty=0');
			$i=0;
			foreach ($categories as $category) {
				$i++;
				$thiscat = esc_attr($instance['category-'.$i]);
				
				if(checked('1', $thiscat ,false)){
					
					$cats .= $category->cat_ID .',';
				}
				
			}
			
			query_posts('cat='.$cats.'&order=DESC');
			the_post();
			
			
			/* Before widget (defined by themes). */
			echo $before_widget;
			echo '<link type="text/css" href="'. plugin_dir_url(__FILE__).'/pblBookshelf/css/styles.css" rel="stylesheet">';
			/* Display the widget title if one was input (before and after defined by themes). */
			if ( $title )
			echo $before_title . $title . $after_title;
			
			
		?>
		<div id="pblBookshelf-daddy">
			<div id="pblBookshelf-book">
				<div id="pblBookshelf-thumb">
					<?php 
						if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
							the_post_thumbnail(array(100,100));
						} 
					?>
				</div>
				<div id="pblBookshelf-desc">
					<a href="<?php echo get_permalink(); ?>"><p><?php the_title(); ?></p></a>
					<!--<p><?php echo substr(get_the_content(), 0 , 100); ?></p>
					<a href="<?php echo get_permalink(); ?>">Διαβάστε περισσότερα.</a>-->
				</div>
			</div>
		</div>
		<div id="pblBookshelf-bookself"></div>
		<?php
			/* After widget (defined by themes). */
			echo $after_widget;
			
		}
		
		
		
		/*Saves the settings. */
		
		function update($new_instance, $old_instance){
			
			$instance = $old_instance;
			/* Strip tags for title and name to remove HTML (important for text inputs). */
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['catnumber'] = strip_tags($new_instance['catnumber']);
			$catnumber = $instance['catnumber'];
			$i=1;
			while($i<=$catnumber){
				$instance['category-'.$i] =  strip_tags($new_instance['category-'.$i]);
				$i++;
			}
			
			return $instance;
			
		}
		
		
		
		/*Creates the form for the widget in the back-end. */
		
		function form($instance){
			
			/* Set up some default widget settings. */
			$defaults = array( 'title' => __('Title', 'pblBookshelf'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		
		<!-- Sex: Select Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'categories' ); ?>"><?php _e('Categories', 'pblBookshelf'); ?></label> 
			<br>
			
			<?php 
				
				$categories=  get_categories('hide_empty=0');
				$i=0;
				foreach ($categories as $category) {
					$i++;
					$thiscat = esc_attr($instance['category-'.$i]);
					
					$option = '<input type="checkbox" id="'.$this->get_field_id('category-'.$i).'" name="'.$this->get_field_name('category-'.$i).'" value="1" '.strip_tags(checked('1', $thiscat ,false)	).'/>';
					$option .= $category->cat_name;
					$option .= ' ('.$category->category_count.')';
					$option .= '</br>';
					echo $option;
					
				}
			?>
			<input type="hidden" value="<?php echo $i;?>" id="<?php echo $this->get_field_id( 'catnumber' ); ?>" name="<?php echo $this->get_field_name( 'catnumber' ); ?>">
			
		</p>
		<?php
			
		}
		
		
		
	}// end pblBookshelf class
	
	
	
	function pblBookshelfInit() {
		
		register_widget('pblBookshelf');
		
	}
	
	
	
	add_action('widgets_init', 'pblBookshelfInit');
	
	
	
?>