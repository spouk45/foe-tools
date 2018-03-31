<?php 
include('../config.php');
include(HEAD);
include(HEADER);
include(DIR_ROOT.'admin/inc/check_admin.php');

?>
<article id="admin">
<p><a href="<?php echo URL_ROOT.'admin/news.php';?>">Les news</a></p>
<p><a href="<?php echo URL_ROOT.'admin/seek.php';?>">Les recherches</a></p>
</article>