<?php
	include "session.php";
?>
<div id="sidebar">
	<div class="cog">+</div>
	<div class="user_box clearfix">
		<strong><?php echo $_SESSION['employee_name']; ?></strong><br>		
		<?php echo $_SESSION['employee_designation']; ?>
		<?php echo get_userbranch($_SESSION['user_id']); ?>
		</h3>
	</div>
	<ul id="accordion">
		<li><a href="../logout.php"><img src="../images/logout.png"/>Logout</a></li>
		<li><a href="changepassword.php"><img src="../images/key.png"/>Change Password</a></li>
	</ul>
</div>
