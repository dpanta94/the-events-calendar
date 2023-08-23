<?php
/**
 * View: Default Template for the Single Events on FSE.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/blocks/single-event.php
 *
 * See more documentation about our views templating system.
 *
 * @link    http://evnt.is/1aiy
 *
 * @version TBD
 */

use Tribe\Events\Views\V2\Assets as Event_Assets;
use Tribe\Events\Views\V2\Template_Bootstrap;

tribe_asset_enqueue_group( Event_Assets::$group_key );
tribe_asset_enqueue( 'tec-events-iframe-content-resizer' );
?>

<div class="tribe-block tribe-block__single-event">
	<?php echo tribe( Template_Bootstrap::class )->get_view_html(); ?>
</div>
