<?php

	/*
	* Copyright (C) 2016-2018 Abre.io Inc.
	*
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the Affero General Public License version 3
    * as published by the Free Software Foundation.
	*
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU Affero General Public License for more details.
	*
    * You should have received a copy of the Affero General Public License
    * version 3 along with this program.  If not, see https://www.gnu.org/licenses/agpl-3.0.en.html.
    */

    //Required configuration files
	require(dirname(__FILE__) . '/../../configuration.php');
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');

	//Check for installation
	if(superadmin()){ require('installer.php'); }

	$pageview = 1;
	$drawerhidden = 1;
	$pageorder = 6;
	$pagetitle = "Profile";
	$pageicon = "account_circle";
	$pagepath = "profile";
	$pagerestrictions = "";

?>

	<!--Profile modal-->
	<div id='viewprofile_arrow' class='hide-on-small-only'></div>
	<div id="viewprofile" class="modal apps_modal modal-mobile-full">
		<div class="modal-content">
			<a class="modal-close black-text hide-on-med-and-up" style='position:absolute; right:20px; top:25px;'><i class='material-icons'>clear</i></a>
			<?php
				echo "<div class='row' style='margin-bottom:0;'>";
					echo "<p style='text-align:center; font-weight:600; margin-bottom:0;' class='truncate'>".$_SESSION['displayName']."</p>";
					echo "<p style='text-align:center;' class='truncate'>".$_SESSION['useremail']."</p>";
					echo "<p style='text-align:center; font-weight:600;' class='truncate'><img src='".$_SESSION['picture']."?sz=100' style='width:100px; height:100px;' class='circle'></p>";
					echo "<hr style='margin-bottom:20px;'>";
					echo "<p style='text-align:center;'><a class='waves-effect btn-flat white-text myprofilebutton' href='#profile' style='margin-right:5px; background-color:"; echo getSiteColor(); echo "'>My Profile</a>";
					echo "<a class='waves-effect btn-flat white-text' href='?signout' style='background-color:"; echo getSiteColor(); echo "'>Sign Out</a></p>";
				echo "</div>";
			?>
    	</div>
	</div>

<script>

	$(function(){

    $('.modal-viewprofile').leanModal({
			in_duration: 0,
			out_duration: 0,
			opacity: 0,
    	ready: function() {
	    	$("#viewprofile_arrow").show();
	    	$("#viewprofile").scrollTop(0);
	    	$('#viewapps').closeModal({
		    	in_duration: 0,
					out_duration: 0,
		   	});
	    	$("#viewapps_arrow").hide();
	    },
    	complete: function() { $("#viewprofile_arrow").hide(); }
   	});

	  	//Make the Profile Icon Clickable/Closeable
		$(".myprofilebutton").unbind().click(function(){
			//Close the app modal
			$("#viewprofile_arrow").hide();
			$('#viewprofile').closeModal({
				in_duration: 0,
				out_duration: 0,
		  });
		});

	});

</script>