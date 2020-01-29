<?php header("Content-type: text/css; charset: UTF-8");
  
  $home_intro_backup_image = '#123 url(' . get_header_image() . ')';
  $home_intro_image = '#123 url(//unsplash.it/900)';



?>

.home-intro {
  background: <?php echo empty($home_intro_image) ? $home_intro_backup_image : $home_intro_image?>;

}