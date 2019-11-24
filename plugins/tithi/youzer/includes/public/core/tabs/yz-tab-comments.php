<?php

class YZ_Comments_Tab {

	/**
	 * Tab Core
	 */
	function tab() { 

		global $Youzer;

		// Comments Tab Arguments
		$args = array(
			'tab_order'	  => 40,
			'tab_name' 	  => 'comments',
			'tab_slug' 	  => 'comments',
            'tab_id'	  => 'youzer-comments',
			'tab_icon'	  => yz_options( 'yz_comments_tab_icon' ),
			'tab_title'   => yz_options( 'yz_comments_tab_title' ),
			'display_tab' => yz_options( 'yz_display_comments_tab' )
		);

	    $Youzer->tabs->core( $args );

	}

    /**
     * # Tab Content.
     */
	function tab_content() {

		// Get Comments Page Number
		$paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;

		// Get Max Comments Per Page
		$commentsNbr = yz_options( 'yz_profile_comments_nbr' );

		// Get Comments Offset
		$offset = ( $paged - 1 ) * $commentsNbr;

		// Comments Pagination Settings
		$args = array(
			'user_id' => yz_profileUserID(),
			'number'  => $commentsNbr,
			'offset'  => $offset,
			'paged'   => $paged
		);

		echo '<div id="yz-main-comments" class="yz-tab yz-tab-comments">';
		$this->comments_core( $args );
		yz_loading();
		echo '</div>';


	}

	/**
	 * # Comments Core.
	 */
	function comments_core( $args ) {

		// Get Base 
		$base = isset( $_POST['base'] ) ? $_POST['base'] : get_pagenum_link( 1 );

		// Get User Comments Number
		$total_comments = yz_get_comments_number( $args['user_id'] );

		// Pagination
		$comments_page = ! empty( $_POST['page'] ) ? $_POST['page'] : 1 ;

		// Query
		$comments_query = new WP_Comment_Query;
		$comments 		= $comments_query->query( $args );

		// Comment Loop
		if ( $comments ) {

			?>

			<div class="yz-comments-page" data-post-page="<?php echo $comments_page; ?>">

			<?php foreach ( $comments as $comment ) : ?>

			<?php

				// Get Comment Data
				$comment_ID 	 = $comment->comment_ID;
				$post_id 		 = $comment->comment_post_ID;
				$comment_content = $comment->comment_content;

				// Get Comment Author Data
				$last_name		 = yz_data( 'last_name', $args['user_id'] );
				$first_name		 = yz_data( 'first_name', $args['user_id'] );
				$comment_author  = yz_data( 'user_login', $args['user_id'] );
				$user_fullname	 = $first_name . ' ' . $last_name;

				// Get Author Image.
				$author_img = bp_core_fetch_avatar( 
					array(
						'item_id' => $args['user_id'],
						'type'	  => 'full',
						'html' 	  => false,
					)
				);
				
				// Get Comment Url
				$post_url	 = get_the_permalink( $post_id );
				$comment_url = $post_url . "#comment-" . $comment_ID;

				// Show / Hide Comment Elements
				$display_date 	  = yz_options( 'yz_display_comment_date' );
				$display_button   = yz_options( 'yz_display_view_comment' );
				$display_username = yz_options( 'yz_display_comment_username' );

			?>

			<div class="yz-tab-comment">
				<div class="yz-comment-content">
					<div class="yz-comment-head">
						<div class="yz-comment-img" style="background-image: url(<?php echo $author_img; ?>);"></div>
						<div class="yz-comment-meta">
							<h3 class="yz-comment-fullname"><?php  echo $user_fullname; ?></h3>
							<?php if ( 'on' == $display_button ) : ?>
								<a href="<?php echo $comment_url; ?>" class="view-comment-button">
									<i class="fas fa-comment-dots"></i><?php _e( 'View Comment', 'youzer' ); ?>
								</a>
							<?php endif; ?>
							<ul>
								<?php if ( 'on' == $display_username ) : ?>
								<li class="yz-comment-author">@<?php  echo $comment_author; ?></li>
								<?php endif; ?>
								<?php if ( 'on' == $display_date ) : ?>
								<li class="yz-comment-date"><span>&#8226;</span><?php comment_date( 'F j, Y', $comment_ID ); ?></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
					<div class="yz-comment-excerpt">
						<p><?php echo yz_get_excerpt( $comment_content , 50 ); ?></p>
					</div>
				</div>
			</div>

			<?php endforeach; ?>

		<?php $this->pagination( $total_comments, $base ); ?>

		</div>

		<?php } else { ?>

		<div class="yz-info-msg yz-failure-msg">
			<div class="yz-msg-icon">
				<i class="fas fa-exclamation-triangle"></i>
			</div>
		 	<p><?php _e( 'Sorry, no comments found !', 'youzer' ); ?></p>
		 </div>

		<?php

		}

	}

	/**
	 * # Pagination.
	 */
	function pagination( $total_comments, $base = null ) {

		//Get Comments Per Page Number
		$commentsNbr = yz_options( 'yz_profile_comments_nbr' );
		$commentsNbr = $commentsNbr ? $commentsNbr : 1;

		// Get total Pages Number
		$max_page = ceil( $total_comments / $commentsNbr );

		// Get Current Page Number
		$cpage = ! empty( $_POST['page'] ) ?  $_POST['page'] : 1 ;

		// Get Next and Previous Pages Number
		if ( ! empty( $cpage ) ) {
			$next_page = $cpage + 1;
			$prev_page = $cpage - 1;
		}

		// Pagination Settings
		$comments_args = array(
			'base'        => $base . '%_%',
			'format' 	  => 'page/%#%',
			'total'       => $max_page,
			'current'     => $cpage,
			'show_all'    => false,
			'end_size'    => 1,
			'mid_size'    => 2,
			'prev_next'   => True,
			'prev_text'   => '<div class="yz-page-symbole">&laquo;</div><span class="yz-next-nbr">'. $prev_page .'</span>',
			'next_text'   => '<div class="yz-page-symbole">&raquo;</div><span class="yz-next-nbr">'. $next_page .'</span>',
			'type'         => 'plain',
			'add_args'     => false,
			'add_fragment' => '',
			'before_page_number' => '<span class="yz-page-nbr">',
			'after_page_number'  => '</span>',
		);

		// Call Pagination Function
		$paginate_comments = paginate_links( $comments_args );

		// Print Comments Pagination
		if ( $paginate_comments ) {
			echo sprintf( '<nav class="yz-pagination" data-base="%1s">' , $base );
			echo '<span class="yz-pagination-pages">';
			printf( __( 'Page %1$d of %2$d' , 'youzer' ), $cpage, $max_page );
			echo "</span><div class='comments-nav-links yz-nav-links'>$paginate_comments</div></nav>";
		}
	}

}