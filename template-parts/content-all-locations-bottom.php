<?php 
	$include_locations_section = get_field('include_the_section', 'options');

	if($include_locations_section == true):

		$locations_args = array( 
			'offset' => 0,
			'posts_per_page' => -1,
			'post_type' => 'locations', 
			'orderby' => 'title', 
			'order' => 'ASC',
			'post_status' => 'publish'	
		);
		$locations = get_posts( $locations_args );
		$location_count = count($locations);
		$count_half = ceil($location_count / 2);
		$item_count = 0;

		$section_title = get_field('all_locations_section_title', 'options');
		$custom_background = get_field('all_locations_section_background', 'options');

		$background_image = ($custom_background ? $custom_background['sizes']['large'] : get_stylesheet_directory_uri() . '/images/footer-cta-bkd.jpg');

		if($location_count > 0):
		?>
			<div id="all-locations-container" style="background-image:url(<?php echo $background_image; ?>);">
				<div class="container">
					<div class="locations-content">
						<h2><?php echo $section_title ?></h2>
						<div class="row">
							<div class="col-md-6">
								<ul>

								<?php
									foreach ( $locations as $location ): ?>
										<?php 
											$item_count++;
											$location_types = $location->facility_type;
											$specialty_types = '';

											if(!empty($location_types)){
												$types_count = count($location_types);
												$i = 0;
												$specialty_types = ' (';
												foreach ($location_types as $type) {
													$i++;
													$specialty_types.= $type;
													if($types_count > $i){
														$specialty_types.= ', ';
													}
												}
													$specialty_types .= ')';
											}
										 ?>
										<li>
											<a href="<?php echo get_the_permalink($location->ID); ?>"><?php echo $location->post_title . $specialty_types ?></a>
										</li>

										<?php 
											if($count_half == $item_count){
												echo '</ul></div><div class="col-md-6"><ul>';
											}
										 ?>
								<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
	<?php endif; ?>
<?php endif; ?>