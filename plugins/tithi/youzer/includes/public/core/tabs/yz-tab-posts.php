<?php

class YZ_Posts_Tab {

	/**
	 * Tab Core
	 */
	function tab() {
		
		global $Youzer;

		// Posts Tab Arguments
		$args = array(
			'tab_order'	  => 30,
			'tab_name' 	  => 'posts',
			'tab_slug' 	  => 'posts',
            'tab_id'	  => 'youzer-posts',
			'tab_icon'	  => yz_options( 'yz_posts_tab_icon' ),
			'tab_title'   => yz_options( 'yz_posts_tab_title' ),
			'display_tab' => yz_options( 'yz_display_posts_tab' )
		);

	    $Youzer->tabs->core( $args );
	}

	/**
	 * # Tab Content
	 */
	function tab_content() {

		// Get Data.
		$postsNbr = yz_options( 'yz_profile_posts_per_page' );
		$paged 	  = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;

		// Prepare Posts Arguments.
		$args = array(
			'order' 		 => 'DESC',
			'paged' 		 => $paged,
			'post_status'	 => 'publish',
			'posts_per_page' => $postsNbr,
			'author' 		 => yz_profileUserID(),
		);

		echo '<div id="yz-main-posts" class="yz-tab yz-tab-posts">';
		$this->posts_core( $args );
		yz_loading();
		echo '</div>';

	}

	/**
	 * # Post Core .
	 */
	function posts_core( $args ) {

		$blogs_ids = is_multisite() ? get_sites() : array( (object) array( 'blog_id' => 1 ) );
		
		$blogs_ids = apply_filters( 'yz_profile_posts_tab_blog_ids', $blogs_ids );

		foreach( $blogs_ids as $b ) {

		    switch_to_blog( $b->blog_id );

			// Posts Pagination
			$posts_page = ! empty( $_POST['page'] ) ? $_POST['page'] : 1 ;

			// init WP Query
			$posts_query = new WP_Query( $args );

			// Get Base 
			$base = isset( $_POST['base'] ) ? $_POST['base'] : get_pagenum_link( 1 );

		?>

		<div class="yz-posts-page" data-post-page="<?php echo $posts_page; ?>">

			<?php if ( $posts_query->have_posts() ) : ?>

			<?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>

			<?php

				// Get Post Data
				$post 				= get_post( $args['author'] );
				$post_id 			= $posts_query->post->ID;
				$post_excerpt 		= yz_get_excerpt( get_the_content(), 25 );
				$post_comments_nbr 	= wp_count_comments( $post_id );

				// Show / Hide Post Elements
				$display_meta 		= yz_options( 'yz_display_post_meta' );
				$display_date 		= yz_options( 'yz_display_post_date' );
				$display_cats 		= yz_options( 'yz_display_post_cats' );
				$display_excerpt	= yz_options( 'yz_display_post_excerpt' );
				$display_readmore 	= yz_options( 'yz_display_post_readmore' );
				$display_comments 	= yz_options( 'yz_display_post_comments' );
				$display_meta_icons = yz_options( 'yz_display_post_meta_icons' );

			?>

			<div class="yz-tab-post">

				<?php yz_get_post_thumbnail( array( 'post_id' => get_the_ID() ) ); ?>

				<div class="yz-post-container">

					<div class="yz-post-inner-content">

						<div class="yz-post-head">

							<h2 class="yz-post-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<?php if ( 'on' == $display_meta ) : ?>

							<div class="yz-post-meta">

								<ul>

									<?php if ( 'on' == $display_date ) : ?>
										<li>
											<?php if ( 'on' == $display_meta_icons ) : ?>
												<i class="far fa-calendar-alt"></i>
											<?php endif; ?>
											<?php echo get_the_date(); ?>
										</li>
									<?php endif; ?>

									<?php if ( 'on' == $display_cats ) : ?>

									<?php yz_get_post_categories( $post_id, $display_meta_icons ); ?>

									<?php endif; ?>

									<?php if ( 'on' == $display_comments ) : ?>
										<li>
											<?php if ( 'on' == $display_meta_icons ) : ?>
												<i class="far fa-comments"></i>
											<?php endif; ?>
											<?php echo $post_comments_nbr->total_comments; ?>
										</li>
									<?php endif; ?>

								</ul>

							</div>

							<?php endif; ?>

						</div>
						<?php if ( 'on' == $display_excerpt ) : ?>
						<div class="yz-post-text">
							<p><?php echo $post_excerpt; ?></p>
						</div>
						<?php endif; ?>

						<?php if ( 'on' == $display_readmore ) : ?>
							<a href="<?php the_permalink(); ?>" class="yz-read-more">
								<div class="yz-rm-icon">
									<i class="fas fa-angle-double-right"></i>
								</div>
								<?php _e( 'read more', 'youzer' ); ?>
							</a>
						<?php endif; ?>

					</div>

				</div>

			</div>

			<?php endwhile;?>
			<?php wp_reset_postdata(); ?>
		    <?php $this->pagination( $posts_query->max_num_pages, $base ); ?>
			<?php else: ?>

			<div class="yz-info-msg yz-failure-msg">
				<div class="yz-msg-icon">
					<i class="fas fa-exclamation-triangle"></i>
				</div>
			 	<p><?php _e( 'Sorry, no posts found !', 'youzer' ); ?></p>
			 </div>

			<?php endif; ?>

		</div>

		<?php
		
		    restore_current_blog();
		}
	}

	/**
	 * # Pagination.
	 */
	function pagination( $numpages = '', $base = null ) {

		// Get current Page Number
		$paged = ! empty( $_POST['page'] ) ? $_POST['page'] : 1 ;

		// Get Total Pages Number
		if ( $numpages == '' ) {
			global $wp_query;
			$numpages = $wp_query->max_num_pages;
			if ( ! $numpages ) {
				$numpages = 1;
			}
		}

		// Get Next and Previous Pages Number
		if ( ! empty( $paged ) ) {
			$next_page = $paged + 1;
			$prev_page = $paged - 1;
		}

		// Pagination Settings
		$pagination_args = array(
			'base'            		=> $base . '%_%',
			'format'          		=> 'page/%#%',
			'total'           		=> $numpages,
			'current'         		=> $paged,
			'show_all'        		=> False,
			'end_size'        		=> 1,
			'mid_size'        		=> 2,
			'prev_next'       		=> True,
			'prev_text'       		=> '<div class="yz-page-symbole">&laquo;</div><span class="yz-next-nbr">'. $prev_page .'</span>',
			'next_text'       		=> '<div class="yz-page-symbole">&raquo;</div><span class="yz-next-nbr">'. $next_page .'</span>',
			'type'            		=> 'plain',
			'add_args'        		=> false,
			'add_fragment'    		=> '',
			'before_page_number' 	=> '<span class="yz-page-nbr">',
			'after_page_number' 	=> '</span>',
		);

		// Call Pagination Function
		$paginate_links = paginate_links( $pagination_args );

		// Print Pagination
		if ( $paginate_links ) {
			echo sprintf( '<nav class="yz-pagination" data-base="%1s">' , $base );
			echo '<span class="yz-pagination-pages">';
			printf( __( 'Page %1$d of %2$d' , 'youzer' ), $paged, $numpages );
			echo "</span><div class='posts-nav-links yz-nav-links'>$paginate_links</div></nav>";
		}

	}

}