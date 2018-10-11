<?php 
    $email_contact = get_field('contact_email_address','options'); 
    $part_options = get_nectar_theme_options();
?>
<?php if(!empty($part_options['enable_social_in_header']) && $part_options['enable_social_in_header'] == '1') { ?>
    <ul id="social">
        <?php  if(!empty($part_options['use-facebook-icon-header']) && $part_options['use-facebook-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['facebook-url']; ?>"><i class="icon-facebook"></i> </a></li> <?php } ?>
        <?php  if(!empty($part_options['use-linkedin-icon-header']) && $part_options['use-linkedin-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['linkedin-url']; ?>"><i class="icon-linkedin"></i> </a></li> <?php } ?>
        <?php  if(!empty($part_options['use-twitter-icon-header']) && $part_options['use-twitter-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['twitter-url']; ?>"><i class="icon-twitter"></i> </a></li> <?php } ?>
        <?php  if(!empty($part_options['use-vimeo-icon-header']) && $part_options['use-vimeo-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['vimeo-url']; ?>"><i class="icon-vimeo"></i> </a></li> <?php } ?>
        <?php  if(!empty($part_options['use-pinterest-icon-header']) && $part_options['use-pinterest-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['pinterest-url']; ?>"><i class="icon-pinterest"></i> </a></li> <?php } ?>
        <?php  if(!empty($part_options['use-youtube-icon-header']) && $part_options['use-youtube-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['youtube-url']; ?>"><i class="icon-youtube"></i> </a></li> <?php } ?>
        <?php  if(!empty($part_options['use-tumblr-icon-header']) && $part_options['use-tumblr-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['tumblr-url']; ?>"><i class="icon-tumblr"></i> </a></li> <?php } ?>
        <?php  if(!empty($part_options['use-dribbble-icon-header']) && $part_options['use-dribbble-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['dribbble-url']; ?>"><i class="icon-dribbble"></i> </a></li> <?php } ?>
        <?php  if(!empty($part_options['use-rss-icon-header']) && $part_options['use-rss-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo (!empty($part_options['rss-url'])) ? $part_options['rss-url'] : get_bloginfo('rss_url'); ?>"><i class="icon-rss"></i> </a></li> <?php } ?>
        <?php  if(!empty($part_options['use-github-icon-header']) && $part_options['use-github-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['github-url']; ?>"><i class="icon-github-alt"></i></a></li> <?php } ?>
        <?php  if(!empty($part_options['use-behance-icon-header']) && $part_options['use-behance-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['behance-url']; ?>"><i class="icon-be"></i> </a></li> <?php } ?>
        <?php  if(!empty($part_options['use-google-plus-icon-header']) && $part_options['use-google-plus-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['google-plus-url']; ?>"><i class="icon-google-plus"></i> </a></li> <?php } ?>
        <?php  if(!empty($part_options['use-instagram-icon-header']) && $part_options['use-instagram-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['instagram-url']; ?>"><i class="icon-instagram"></i></a></li> <?php } ?>
        <?php  if(!empty($part_options['use-stackexchange-icon-header']) && $part_options['use-stackexchange-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['stackexchange-url']; ?>"><i class="icon-stackexchange"></i></a></li> <?php } ?>
        <?php  if(!empty($part_options['use-soundcloud-icon-header']) && $part_options['use-soundcloud-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['soundcloud-url']; ?>"><i class="icon-soundcloud"></i></a></li> <?php } ?>
        <?php  if(!empty($part_options['use-flickr-icon-header']) && $part_options['use-flickr-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['flickr-url']; ?>"><i class="icon-flickr"></i></a></li> <?php } ?>
        <?php  if(!empty($part_options['use-spotify-icon-header']) && $part_options['use-spotify-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['spotify-url']; ?>"><i class="icon-salient-spotify"></i></a></li> <?php } ?>
        <?php  if(!empty($part_options['use-vk-icon-header']) && $part_options['use-vk-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['vk-url']; ?>"><i class="icon-vk"></i></a></li> <?php } ?>
        <?php  if(!empty($part_options['use-vine-icon-header']) && $part_options['use-vine-icon-header'] == 1) { ?> <li><a target="_blank" href="<?php echo $part_options['vine-url']; ?>"><i class="fa-vine"></i></a></li> <?php } ?>
    </ul>
<?php } ?>
