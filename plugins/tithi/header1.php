<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="format-detection" content="telephone=no" />
	<title><?php wp_title(); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<!--[if lt IE 9]><script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script><![endif]-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	
<link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet">

	<link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500' rel='stylesheet' type='text/css'>
	<?php wp_head(); ?>
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
	<style>
		.wpsocial-social-icons-box #wpsocial-user-sharing-message,
		.wpsocial-social-icons-box .wpsocial-user-sharing-message{
			font-family: 'Roboto', sans-serif !important;
			font-size:15px !important;
			line-height: 1.5 !important;
		}
	</style>
	<style>
		@media(max-width: 993px){
			.header_tithi{
				width: 100% !important;
				overflow-x: auto !important;
			}
		}
	</style>
</head>
<body>
<div class="wrapper clearfix">

<section class="absolute-header clearfix">
	<aside class="float-left">
		<?php //echo date("M d, Y") ?>
	</aside>
	<aside class="float-right">
		<a href="#" target="_blank"><i class="fa fa-facebook fa-fw"></i></a>
		<a href="#" target="_blank"><i class="fa fa-twitter fa-fw"></i></a>
		<a href="#" target="_blank"><i class="fa fa-google-plus fa-fw"></i></a>
		<a href="#" target="_blank"><i class="fa fa-instagram fa-fw"></i></a>
		<a href="#" target="_blank"><i class="fa fa-youtube fa-fw"></i></a>
	</aside>
</section>

<header class="site-header clearfix">
	<aside class="logo">
		<a href="<?php echo site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png" alt="<?php echo bloginfo(); ?>" /></a>
	</aside>
	<aside class="header-banner">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/banner.jpg" alt="" />
	</aside>
</header>
<?php if(!is_page_template('template.payment.php')) { ?>
<?php
	if ( get_query_var('paged') )
	$paged = get_query_var('paged');
	elseif ( get_query_var('page') )
	$paged = get_query_var('page');
	else
	$paged = 1;

	$args = array(
	'post_type' => 'post',
	'paged' => $paged );
	query_posts($args);
?>
<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>
<?php if (in_category('English')) { ?>
	<?php $header_sunrise = get_field('sunrise'); ?>
	<?php $header_temperature = get_field('temperature'); ?>
	<?php $header_sunset = get_field('sunset'); ?>
	<?php $header_historic_highest = get_field('historic_highest'); ?>
	<?php $header_historic_lowest = get_field('historic_lowest'); ?>
<?php } elseif (in_category('Yearly')){ ?>
	<?php $header_vikram_samvat_jaartelling = get_field('vikram_samvat_jaartelling'); ?>
	<?php $header_manuwantar_zevende = get_field('manuwantar_zevende'); ?>
	<?php $header_wedische_jaartelling = get_field('wedische_jaartelling'); ?>
	<?php $header_kalyug_jaartelling = get_field('kalyug_jaartelling'); ?>
<?php } elseif (in_category('Hindi')){ ?>
	<?php $header_paksh_details = get_field('paksh_details'); ?>
	<?php $header_moon_state = get_field('moon_state'); ?>
	<?php $dateM=do_shortcode('[postexpirator]');
	     
	 ?>
	
<?php } else { ?>
    <?php // DO NOTHING ?>
<?php } ?>
<?php endwhile; ?>
<?php endif; ?>
<?php wp_reset_query(); ?>
<section class="header-info clearfix">
<div class="">
<?php

          $dateN=date('Y-m-d',strtotime($dateM));
	      $now = date('y-m-d h:i:sa'); // or your date as well
          $your_date = strtotime($dateN);
		  $now1 = strtotime($now);
		 $now2=date('y-m-d h:i:sa',strtotime('+ 5 hour',$now1));
		 $now3=date('y-m-d h:i:sa',strtotime('+ 32 minute',strtotime($now2)));
          $datediff =  $your_date-strtotime($now3);
          $diff=round($datediff / (60 * 60 * 24));
		  ?>
	<table cellspacing="0" cellpadding="0" border="0" class="desktop-table-view" >
		<tr>
			<th>Date & Time</th>
			<td class="header-time"><?php if ( is_active_sidebar( 'header-box-2' ) ) : ?><?php dynamic_sidebar( 'header-box-2' ); ?><?php endif; ?> </td>
			<th>Vikram Samvat / Jaartelling</th>
			<td><?php echo $header_vikram_samvat_jaartelling; ?></td>
			<th>Zonsopkomst</th>
			<td><?php echo $header_sunrise; ?></td>
			<!--<th>Temperatuur</th>
			<td><?php echo $header_temperature; ?></td>-->
		</tr>
		<tr>
			<th>Tithi / Maanstand </th>
			<td class="moon_header_paksha">
				<img src="http://www.dhoom.nl/wp-content/uploads/2018/07/state-<?php echo $header_paksh_details; ?><?php echo $header_moon_state; ?>.jpg" alt="" class="moon-state-img float-right floatright" height="25" />
				<?php if($header_paksh_details == 'shukla') { echo 'Shukla Paksha <br>'; } else if($header_paksh_details == 'krishna') { echo 'Krishna Paksha <br>'; } else { } ?>
				<?php if($diff==14) {
					echo 'Pratipad';
				} else if($diff==13) {
					echo 'Dwitiya';
				} else if($diff==12) {
					echo 'Tritiya';
				} else if($diff==11) {
					echo 'Chaturthi';
				} else if($diff==10) {
					echo 'Panchami';
				} else if($diff==9) {
					echo 'Shashthi';
				} else if($diff==8) {
					echo 'Saptami';
				} else if($diff==7) {
					echo 'Ashtami';
				} else if($diff==6) {
					echo 'Navami';
				} else if($diff==5) {
					echo 'Dasami';
				} else if($diff==4) {
					echo 'Ekadasi';
				} else if($diff==3) {
					echo 'Dwadasi';
				} else if($diff==2) {
					echo 'Trayodasi';
				} else if($diff==1) {
					echo 'Chaturdasi';
				} else if($header_paksh_details == 'shukla' && $diff==0) {
					echo 'Purnima';
				} else if($header_paksh_details == 'krishna' && $diff==0) {
					echo 'Amavasya';
				} else {
					
				} ?>
				
			</td>
			<th>Manuwantar (Zevende) </th>
			<td><?php echo $header_manuwantar_zevende; ?></td>
			<th style="width:30%">Zonsondergang</th>
			<td><?php echo $header_sunset; ?></td>
			<!--<th>Hist.Max</th>
			<td><?php echo $header_historic_highest; ?></td>-->
		</tr>
		<tr>
			<th>Wedische Jaartelling</th>
			<td><?php echo $header_wedische_jaartelling; ?></td>
			<th>Kalyug Jaartelling</th>
			<td><?php echo $header_kalyug_jaartelling; ?></td>
			<th></th>
			<td></td>
			<!--<th>Hist.Min</th>
			<td><?php echo $header_historic_lowest; ?></td>-->
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0" class="ipad-table-view" style="display:none;">
		<tr>
			<th style="width:50%">Date & Time</th>
			<td class="header-time"><?php if ( is_active_sidebar( 'header-box-3' ) ) : ?><?php dynamic_sidebar( 'header-box-3' ); ?><?php endif; ?> </td>
			<th>Vikram Samvat / Jaartelling</th>
			<td><?php echo $header_vikram_samvat_jaartelling; ?></td>
			
			<!--<th>Temperatuur</th>
			<td><?php echo $header_temperature; ?></td>-->
		</tr>
		<tr>
			<th>Tithi / Maanstand </th>
			<td class="moon_header_paksha">
				<img src="http://www.dhoom.nl/wp-content/uploads/2018/07/state-<?php echo $header_paksh_details; ?><?php echo $header_moon_state; ?>.jpg" alt="" class="moon-state-img float-right floatright" height="25" />
				<?php if($header_paksh_details == 'shukla') { echo 'Shukla Paksha <br>'; } else if($header_paksh_details == 'krishna') { echo 'Krishna Paksha <br>'; } else { } ?>
				<?php if($diff==14) {
					echo 'Pratipad';
				} else if($diff==13) {
					echo 'Dwitiya';
				} else if($diff==12) {
					echo 'Tritiya';
				} else if($diff==11) {
					echo 'Chaturthi';
				} else if($diff==10) {
					echo 'Panchami';
				} else if($diff==9) {
					echo 'Shashthi';
				} else if($diff==8) {
					echo 'Saptami';
				} else if($diff==7) {
					echo 'Ashtami';
				} else if($diff==6) {
					echo 'Navami';
				} else if($diff==5) {
					echo 'Dasami';
				} else if($diff==4) {
					echo 'Ekadasi';
				} else if($diff==3) {
					echo 'Dwadasi';
				} else if($diff==2) {
					echo 'Trayodasi';
				} else if($diff==1) {
					echo 'Chaturdasi';
				} else if($header_paksh_details == 'shukla' && $diff==0) {
					echo 'Purnima';
				} else if($header_paksh_details == 'krishna' && $diff==0) {
					echo 'Amavasya';
				} else {
					
				} ?>
				
			</td>
			<th>Manuwantar (Zevende) </th>
			<td><?php echo $header_manuwantar_zevende; ?></td>
			
			<!--<th>Hist.Max</th>
			<td><?php echo $header_historic_highest; ?></td>-->
		</tr>
		<tr>
			<th>Wedische Jaartelling</th>
			<td><?php echo $header_wedische_jaartelling; ?></td>
			<th>Kalyug Jaartelling</th>
			<td><?php echo $header_kalyug_jaartelling; ?></td>
			
			<!--<th>Hist.Min</th>
			<td><?php echo $header_historic_lowest; ?></td>-->
		</tr>
		<tr>
			<th >Zonsondergang</th>
			<td><?php echo $header_sunset; ?></td>
			<th>Zonsopkomst</th>
			<td><?php echo $header_sunrise; ?></td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0" class="mobile-table-view">
		<tr>
			<th>Date & Time</th>
			<td class="header-time"><?php if ( is_active_sidebar( 'header-box-4' ) ) : ?><?php dynamic_sidebar( 'header-box-4' ); ?><?php endif; ?></td>
		</tr>
		<tr>
			<th>Tithi / Maanstand</th>
			<td class="moon_header_paksha">
				<img src="http://www.dhoom.nl/wp-content/uploads/2018/07/state-<?php echo $header_paksh_details; ?><?php echo $header_moon_state; ?>.jpg" alt="" class="moon-state-img float-right floatright" height="25" />
				<?php if($header_paksh_details == 'shukla') { echo 'Shukla Paksha <br>'; } else if($header_paksh_details == 'krishna') { echo 'Krishna Paksha <br>'; } else { } ?>
				<?php if($diff==14) {
					echo 'Pratipad';
				} else if($diff==13) {
					echo 'Dwitiya';
				} else if($diff==12) {
					echo 'Tritiya';
				} else if($diff==11) {
					echo 'Chaturthi';
				} else if($diff==10) {
					echo 'Panchami';
				} else if($diff==9) {
					echo 'Shashthi';
				} else if($diff==8) {
					echo 'Saptami';
				} else if($diff==7) {
					echo 'Ashtami';
				} else if($diff==6) {
					echo 'Navami';
				} else if($diff==5) {
					echo 'Dasami';
				} else if($diff==4) {
					echo 'Ekadasi';
				} else if($diff==3) {
					echo 'Dwadasi';
				} else if($diff==2) {
					echo 'Trayodasi';
				} else if($diff==1) {
					echo 'Chaturdasi';
				} else if($header_paksh_details == 'shukla' && $diff==0) {
					echo 'Purnima';
				} else if($header_paksh_details == 'krishna' && $diff==0) {
					echo 'Amavasya';
				} else {
					
				} ?>
			</td>
		</tr>
		<tr>
			<th>Wedische Jaartelling</th>
			<td><?php echo $header_wedische_jaartelling; ?></td>
		</tr>
		<tr>
			<th>Vikram Samvat / Jaartelling</th>
			<td><?php echo $header_vikram_samvat_jaartelling; ?></td>
		</tr>
		<tr>
			<th>Manuwantar (Zevende)</th>
			<td><?php echo $header_manuwantar_zevende; ?></td>
		</tr>
		<tr>
			<th>Kalyug Jaartelling</th>
			<td><?php echo $header_kalyug_jaartelling; ?></td>
		</tr>
		<tr>
			<th>Zonsopkomst</th>
			<td><?php echo $header_sunrise; ?></td>
		</tr>
		<tr>
			<th>Zonsondergang</th>
			<td><?php echo $header_sunset; ?></td>
		</tr>
		<tr>
			<th>Temperatuur</th>
			<td><?php echo $header_temperature; ?></td>
		</tr>
	</table>
	</div>
</section>
<section class="clearfix">
<?php echo do_shortcode('[News-Ticker]'); ?>
</section>
<?php } else { ?>
<?php } ?>
<?php /* ?>
<section class="header-info clearfix">
	<aside class="header-box">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/header-top-1.jpg" alt="" />
	</aside>
	<aside class="header-box">
		<div class="box clearfix">
            <?php if ( is_active_sidebar( 'header-box-2' ) ) : ?>
                <?php dynamic_sidebar( 'header-box-2' ); ?>
            <?php endif; ?>
		</div>
	</aside>
	<aside class="header-box">
		<div class="box clearfix">
            <?php if ( is_active_sidebar( 'header-box-3' ) ) : ?>
                <?php dynamic_sidebar( 'header-box-3' ); ?>
            <?php endif ?>
		</div>
	</aside>
	<aside class="header-box">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/header-top-4.png" alt="" />
	</aside>
</section>
<?php */ ?>

<nav class="site-navigation clearfix">
	<a href="#" class="nav-toggle"><i class="fa fa-bars"></i> Menu</a>
	<aside class="nav-box">
		<?php wp_nav_menu( array( 'theme_location' => 'primary-menu' ) ); ?>
	</aside>
</nav>

<section class="site-content clearfix">
	<aside class="main-content">
