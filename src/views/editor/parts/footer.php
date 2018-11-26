<?php
/**
 * Single Event Footer Template Part
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/editor/parts/footer.php
 *
 * See more documentation about our Blocks Editor templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version TBD
 *
 */
?>

<?php
$events_label_singular = tribe_get_event_label_singular();
?>
<div id="tribe-events-footer">
	<h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?></h3>
	<ul class="tribe-events-sub-nav">
		<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
		<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
	</ul>
</div>
