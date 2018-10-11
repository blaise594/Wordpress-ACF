
<div id="facility" class="locations-grid-container">
    <div class="container">
        <div class="locations-section urgent-care">
            <?php echo(get_field('locations_grid_urgent_care_title') ? '<h2>' . get_field('locations_grid_urgent_care_title') . '</h2>' : ''); ?>
            <?php echo sal_all_locations_grid_display('urgent-care'); ?>
        </div>
        <div class="locations-section">
            <?php echo(get_field('locations_grid_section_title') ? '<h2>' . get_field('locations_grid_section_title') . '</h2>' : ''); ?>
            <?php echo sal_all_locations_grid_display('not-urgent-care'); ?>
        </div>
    </div>
</div>