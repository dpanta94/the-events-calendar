<?php

use TEC\Events\Custom_Tables\V1\Migration\Reports\Site_Report;
use TEC\Events\Custom_Tables\V1\Migration\Strings;

/**
 * @var string      $template_directory The absolute path to the Migration template root directory.
 * @var Site_Report $report             The report details.
 * @var Strings     $text               Our text dictionary.
 */
?>
<div class="tec-ct1-upgrade__row">
	<div class="content-container">
		<h3>
			<?php
			include $template_directory . '/upgrade-logo.php';
			?>
			<?php echo esc_html( $text->get( 'preview-complete' ) ); ?>
		</h3>

		<p>
			<?php
			echo sprintf(
					esc_html( $text->get( 'preview-complete-paragraph' ) ),
					'<a href="https://evnt.is/2n" rel="noopener" target="_blank">',
					'</a>'
			);
			?>
		</p>

		<p class="tec-ct1-upgrade__alert">
			<i class="tec-ct1-upgrade__alert-icon">!</i>
			<span>
			<?php
			echo sprintf(
					esc_html( $text->get( 'preview-estimate' ) ),
					'<strong>',
					'</strong>',
					$report->estimated_time_in_hours
			);

			if ( $addendum = tribe( \TEC\Events\Custom_Tables\V1\Migration\Admin\Upgrade_Tab::class )->get_migration_prompt_addendum() ) {
				?>
				<strong><?php echo esc_html( $addendum ); ?></strong>
				<?php
			}
			?>
			</span>
			<a href="<?php echo esc_url( $text->get( 'learn-more-button-url' ) ); ?>" target="_blank" rel="noopener">
				<strong><?php echo esc_html( $text->get( 'learn-more-button' ) ); ?></strong>
			</a>
		</p>
	</div>

	<div class="image-container">
		<img class="screenshot"
			 src="<?php echo esc_url( $text->get( 'completed-screenshot-url' ) ); ?>"
			 alt="<?php echo esc_attr( $text->get( 'updated-views-screenshot-alt' ) ); ?>"/>
	</div>
</div>

<div class="tec-ct1-upgrade__row">
	<?php
	$datetime_heading = $text->get( 'previewed-date-heading' );
	$total_heading    = $text->get( 'previewed-total-heading' );
	ob_start();
	?>
	<em title="<?php esc_attr( $text->get( 're-run-preview-button' ) ) ?>">
		<?php include TEC_CUSTOM_TABLES_V1_ROOT . '/admin-views/migration/icons/rerun.php'; ?>
	</em>
	<a class="tec-ct1-upgrade-start-migration-preview"
	   href="#"><?php echo esc_html( $text->get( 're-run-preview-button' ) ); ?></a>
	<?php
	$heading_action = ob_get_clean();
	include_once __DIR__ . '/report.php';
	?>
</div>

<div class="tec-ct1-upgrade__row">
	<div class="content-container">
		<button class="tec-ct1-upgrade-start-migration"
				type="button"><?php echo esc_html( $text->get( 'start-migration-button' ) ); ?></button>
		<i>
			<?php
			if ( $report->estimated_time_in_hours > 1 ) {
				$message = esc_html( $text->get( 'estimated-time-plural' ) );
			} else {
				$message = esc_html( $text->get( 'estimated-time-singular' ) );
			}

			echo sprintf( $message, $report->estimated_time_in_hours );
			?>
		</i>
	</div>
</div>
