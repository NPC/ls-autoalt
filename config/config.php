<?php

$config['add_alt'] = true; // adds ALT if there is none (or if it is empty)
$config['override_title_with_alt'] = false; // copies above ALT text into the title, ignoring what it had before
$config['include_blog_name'] = true; // adds blog name to ALT is it is not already included
$config['ignore_description'] = false; // if true, ALT is always composed of Blog name: Article title, ignoring the original ALT

return $config;
?>