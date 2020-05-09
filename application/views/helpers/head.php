<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dashboard</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="stylesheet" href="<?php //echo base_url(); ?>assets/css/material.min.css" type="text/css"> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/croppie.css" type="text/css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/main.css?v=<?=time();?>" type="text/css">
	<script src="<?php echo base_url(); ?>assets/js/material.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
</head>
<body>
	<div id="main-container">
		<div id="side-menu">
			<a href="#" class="side-menu-logo">
				<div>
					<object type="image/svg+xml" data="<?php echo base_url();?>assets/imgs/logo.svg"></object>
				</div>
			</a>

			<div class="icons-wrap">
				<div class="upper-icons-wrap">
					<a href="#" id="home-icon" class="upper-icons "><img src="<?php echo base_url(); ?>assets/imgs/home.svg"/></a>
					<a href="<?php echo base_url(); ?>Creation" id="creation-icon" class="upper-icons"><img src="<?php echo base_url(); ?>assets/imgs/write.svg"/></a>
					<a href="<?php echo base_url(); ?>Category" id="category-icon" class="upper-icons"><img src="<?php echo base_url(); ?>assets/imgs/category.svg"/></a>
					<a href="<?php echo base_url(); ?>Archive" id="archive-icon" class="upper-icons"><img src="<?php echo base_url(); ?>assets/imgs/archive.svg"/></a>
				</div>
				<div class="lower-icons-wrap">
					<a href="#" class="lower-icons user-img"><img src="<?php echo base_url(); ?>assets/imgs/abhishek.jpg"/></a>
					<a href="#" class="lower-icons"><img src="<?php echo base_url(); ?>assets/imgs/logout.svg"/></a>
				</div>
			</div>
		</div>

		<div id="search-content-wrap">
			<div id="search-wrap">
				<div class="search-icon"><img src="<?php echo base_url(); ?>assets/imgs/search.svg"/></div>
				<div class="search-wrap"><input name="search" type="text" id='search' placeholder="Search"></div>
				<div class="notification-wrap"><img src="<?php echo base_url(); ?>assets/imgs/notification.svg"/></div>
			</div>
			<div id="notifications">
				<div class="notification-header">
					<div class="notification-icon"><img src="<?php echo base_url(); ?>assets/imgs/notification.svg"/></div>
					<div class="notification-text">Notification</div>
					<div class="notification-close notification-wrap"><img src="<?php echo base_url(); ?>assets/imgs/close.png"/></div>
				</div>
			</div>
			<script type="text/javascript">
				var notif_clicked = 0;
				$('.notification-wrap').click(function(){
					if(notif_clicked == 0){
						$('#notifications').addClass('notif_open');
						notif_clicked = 1;
					} else{
						$('#notifications').removeClass('notif_open');
						notif_clicked = 0;
					}
				});
			</script>