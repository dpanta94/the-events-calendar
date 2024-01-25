<?php

namespace Tribe\Events\Editor\Blocks;

use Spatie\Snapshots\MatchesSnapshots;
use TEC\Events\Block_Templates\Single_Venue\Single_Block_Template;
use Tribe\Test\Products\WPBrowser\Views\V2\HtmlTestCase;

class Single_VenueTest extends HtmlTestCase {
	use MatchesSnapshots;

	public function setUp(): void {
		parent::setUp();
		add_filter( 'tribe_editor_should_load_blocks', '__return_true', PHP_INT_MAX );
		tribe( \Tribe__Events__Editor__Provider::class )->register();
	}

	/**
	 * Test that the block contains 'tribe-block' class.
	 */
	public function test_block_contains_tribe_block_class() {
		$block_content = $this->render_single_event_block();
		$this->assertStringContainsString( 'tec-block', $block_content );
	}

	/**
	 * Test that the block contains 'tribe-block__single-event' class.
	 */
	public function test_block_contains_tribe_block_single_event_class() {
		$block_content = $this->render_single_event_block();
		$this->assertStringContainsString( 'tec-block__single-venue', $block_content );
	}

	/**
	 * Utility method to render the block and return the content.
	 */
	private function render_single_event_block(): string {
		return ( new Single_Block_Template() )->render();
	}
}
