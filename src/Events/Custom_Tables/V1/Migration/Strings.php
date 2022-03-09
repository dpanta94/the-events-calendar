<?php
/**
 * A centralized repository of localized, filterable, strings.
 *
 * @since   TBD
 *
 * @package TEC\Events\Custom_Tables\V1\Migration;
 */

namespace TEC\Events\Custom_Tables\V1\Migration;

/**
 * Class Strings.
 *
 * @since   TBD
 *
 * @package TEC\Events\Custom_Tables\V1\Migration;
 */
class Strings {
	/**
	 * Whether the strings have been initialized or not.
	 *
	 * @since TBD
	 *
	 * @var bool
	 */
	private $did_init = false;
	/**
	 * A map from string slugs to their filtered, localized, version.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private $map = [];

	/**
	 * Initializes the strings map filtering it.
	 *
	 * @since TBD
	 *
	 * @return void The method does not return any value and will
	 *              lazily initialize the strings map.
	 */
	private function init() {
		if ( $this->did_init ) {
			return;
		}

		$this->did_init = true;

		/**
		 * Filters the string map that will be used to provide the Migration UI
		 * messages.
		 *
		 * Note: this filter will run only once, the first time a string is requested.
		 *
		 * @since TBD
		 *
		 * @param array<string,string> A map from string keys to their localized, filtered,
		 *                             version.
		 */
		$this->map = apply_filters( 'tec_events_custom_tables_v1_migration_strings', [
			'completed-screenshot-url'       => plugins_url(
			// @todo correct screenshot here
				'src/resources/images/upgrade-views-screenshot.png',
				TRIBE_EVENTS_FILE
			),
			'completed-site-upgraded'        => __(
				'[@todo TEC]Your site is now using the upgraded recurring events system. See the report below to learn ' .
				'how your events may have been adjusted during the migration process.',
				'the-events-calendar'
			),
			'preview-prompt-get-ready'       => __(
				'[@todo TEC]Get ready for the new recurring events!',
				'the-events-calendar'
			),
			'preview-prompt-upgrade-cta'     => __( '[@todo TEC]Upgrade your recurring events.', 'the-events-calendar' ),
			'preview-prompt-features'        => __(
				'[@todo TEC]Faster event editing. Smarter save options. More flexibility. Events Calendar 6.0  ' .
				'is full of features to make managing recurring and connected events better than ever. ' .
				'Before you get started, we need to migrate your existing events into the new system.',
				'the-events-calendar'
			),
			'preview-prompt-ready'           => __(
				'[@todo TEC]Ready to go? The first step is a migration preview.',
				'the-events-calendar'
			),
			'preview-prompt-scan-events'     => __(
				'We\'ll scan all existing events and let you know what to expect from the migration process. You\'ll also get an idea of how long your migration will take. The preview runs in the background, so you’ll be able to continue using your site.',
				'the-events-calendar'
			),
			'learn-more-button-url'          => __(
				'https://evnt.is/recurrence-2-0',
				'the-events-calendar'
			),
			'learn-more-button'              => __(
				'Learn more about the migration',
				'the-events-calendar'
			),
			'start-migration-preview-button' => __(
				'Start migration preview',
				'the-events-calendar'
			),
			'updated-views-screenshot-alt'   => __(
				'screenshot of updated calendar views',
				'the-events-calendar'
			),
			'preview-in-progress'            => __(
				'Migration preview in progress',
				'the-events-calendar'
			),
			'preview-scanning-events'        => __(
				'We\'re scanning your existing events so you’ll know what to expect from the migration process. You can keep using your site and managing events. Check back later for a full preview report and the next steps for migration.',
				'the-events-calendar'
			),
			'preview-complete'               => __(
				'Preview complete',
				'the-events-calendar'
			),
			'preview-complete-paragraph'     => __(
				'The migration preview is done and ready for your review. No changes have been made to your events, but this report shows what adjustments will be made during the migration to the new system. If you have any questions, please %1$sreach out to our support team%2$s.',
				'the-events-calendar'
			),
			'preview-estimate'               => __(
				'From this preview, we estimate that the full migration process will take approximately %3$s hour(s). During migration, %1$syou cannot make changes to your calendar or events.%2$s Your calendar will still be visible on your site.',
				'the-events-calendar'
			),
			'previewed-date-heading'         => __(
				'Previewed Date/Time:',
				'the-events-calendar'
			),
			'previewed-total-heading'        => __(
				'Total Events Previewed:',
				'the-events-calendar'
			),
			're-run-preview-button'          => __(
				'Re-run preview',
				'the-events-calendar'
			),
			'start-migration-button'         => __(
				'Start migration',
				'the-events-calendar'
			),
			'estimated-time-singular'        => __(
				'(Estimated time: %1$s hour)',
				'the-events-calendar'
			),
			'estimated-time-plural'          => __(
				'(Estimated time: %1$s hours)',
				'the-events-calendar'
			),
		] );
	}

	/**
	 * Returns the filtered, localized string for a slug.
	 *
	 * @since TBD
	 *
	 * @param string $key The key to return the string for.
	 *
	 * @return string The filtered localized string for the key,
	 *                or the key itself if no string for the key
	 *                can be found.
	 */
	public function get( $key ) {
		$this->init();

		if ( isset( $this->map[ $key ] ) ) {
			return $this->map[ $key ];
		}

		// If we cannot find a string for the slug, then return the key.
		return $key;
	}
}