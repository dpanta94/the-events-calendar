<?php
/**
 * Event Status Elementor Widget.
 *
 * @since   TBD
 *
 * @package TEC\Events\Integrations\Plugins\Elementor\Widgets
 */

namespace TEC\Events\Integrations\Plugins\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use TEC\Events\Integrations\Plugins\Elementor\Widgets\Contracts\Abstract_Widget;

/**
 * Class Widget_Event_Status
 *
 * @since   TBD
 *
 * @package TEC\Events\Integrations\Plugins\Elementor\Widgets
 */
class Event_Status extends Abstract_Widget {
	use Traits\With_Shared_Controls;
	use Traits\Has_Preview_Data;

	/**
	 * Widget slug.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	protected static string $slug = 'event_status';

	/**
	 * Create the widget title.
	 *
	 * @since TBD
	 *
	 * @return string
	 */
	protected function title(): string {
		return esc_html__( 'Event Status', 'the-events-calendar' );
	}

	/**
	 * Get the template args for the widget.
	 *
	 * @since TBD
	 *
	 * @return array The template args.
	 */
	public function template_args(): array {
		$event = tribe_get_event( $this->get_event_id() );

		if (
			empty( $event )
			|| ! $event instanceof \WP_Post
			|| empty( $event->event_status )
		) {
			return [];
		}

		$is_passed = tribe_is_event( $event->ID ) && tribe_is_past_event( get_post( $event->ID ) );
		$reason    = $event->event_status_reason;
		$settings  = $this->get_settings_for_display();


		return [
			'description_class'  => $this->get_status_description_class(),
			'label_class'        => $this->get_status_label_class(),
			'status'             => $event->event_status,
			'status_label'       => $this->get_status_label( $event ),
			'status_reason'      => $reason,
			'show_status'        => tribe_is_truthy( $settings['show_status'] ?? true ),
			'show_passed'        => tribe_is_truthy( $settings['show_passed'] ?? true ),
			'is_passed'          => tribe_is_truthy( $is_passed ),
			'passed_label'       => $this->get_passed_label_text(),
			'passed_label_class' => $this->get_passed_label_class(),
			'event'              => $event,
		];
	}

	/**
	 * Get the template args for the widget preview.
	 *
	 * @since TBD
	 *
	 * @return array The template args for the preview.
	 */
	protected function preview_args(): array {
		return [
			'description_class'  => $this->get_status_description_class(),
			'label_class'        => $this->get_status_label_class(),
			'status'             => 'postponed',
			'status_label'       => 'Postponed',
			'status_reason'      => __( 'No reason provided.', 'the-events-calendar' ),
			'show_status'        => true,
			'show_passed'        => true,
			'is_passed'          => true,
			'passed_label'       => $this->get_passed_label_text(),
			'passed_label_class' => $this->get_passed_label_class(),
			'event'              => true,
		];
	}

	/**
	 * Get the CSS class for the label.
	 *
	 * @since TBD
	 *
	 * @return string The CSS class for the label.
	 */
	public function get_status_label_class(): string {
		return $this->get_widget_class() . '-label';
	}

	/**
	 * Get the displayed label for the status widget.
	 *
	 * @since TBD
	 *
	 * @param \WP_Post $event The event post object.
	 *
	 * @return string The CSS class for the status label.
	 */
	protected function get_status_label( $event ): ?string {
		if ( empty( $event->event_status ) ) {
			return null;
		}

		$status_labels = new \Tribe\Events\Event_Status\Status_Labels();
		$method        = 'get_' . $event->event_status . '_label';

		if ( ! method_exists( $status_labels, $method ) ) {
			return null;
		}

		return $status_labels->$method();
	}

	/**
	 * Get the CSS class for the passed label.
	 *
	 * @since TBD
	 *
	 * @return string The CSS class for the passed label.
	 */
	public function get_passed_label_class(): string {
		return $this->get_widget_class() . '-passed';
	}

	/**
	 * Get the CSS class for the Status description.
	 *
	 * @since TBD
	 *
	 * @return string The CSS class for the description.
	 */
	public function get_status_description_class(): string {
		return $this->get_widget_class() . '--description';
	}

	/**
	 * Get the CSS class for the status.
	 *
	 * @since TBD
	 *
	 * @param string $status The status.
	 *
	 * @return string The CSS class for the status.
	 */
	public function get_status_class( $status ) {
		$method = 'get_' . $status . '_class';
		return $this->$method();
	}

	/**
	 * Get the CSS class for the postponed label.
	 *
	 * @since TBD
	 *
	 * @return string The CSS class for the postponed label.
	 */
	protected function get_postponed_class(): string {
		return $this->get_status_label_class() . '--postponed';
	}

	/**
	 * Get the CSS class for the canceled .
	 *
	 * @since TBD
	 *
	 * @return string The CSS class for the canceled .
	 */
	protected function get_canceled_class(): string {
		return $this->get_status_label_class() . '--canceled';
	}

	/**
	 * Get the CSS class for the event passed label.
	 *
	 * @since TBD
	 *
	 * @return string The CSS class for the event passed label.
	 */
	protected function get_passed_label_text(): string {
		$label_text = sprintf(
			// Translators: %s is the singular lowercase label for an event, e.g., "event".
			__( 'This %s has passed.', 'tribe-events-calendar-pro' ),
			tribe_get_event_label_singular_lowercase()
		);

		/**
		 * Filters the label text for the event passed widget.
		 *
		 * @since TBD
		 *
		 * @param string       $label_text The label text.
		 * @param Event_Passed $this The event passed widget instance.
		 *
		 * @return string The filtered label text.
		 */
		return apply_filters( 'tec_events_elementor_event_passed_label_text', $label_text, $this );
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		$status_labels   = new \Tribe\Events\Event_Status\Status_Labels();
		$canceled_label  = esc_html( $status_labels->get_canceled_label() );
		$postponed_label = esc_html( $status_labels->get_postponed_label() );
		// Preview postponed stati if setting(s) are enabled.
		?>
		<# if ( settings.show_passed_status_preview && settings.show_passed ) { #>
			<p <?php tribe_classes( $this->get_passed_label_class() ); ?>><?php echo wp_kses_post( $this->get_passed_label_text() ); ?></p>
		<# } #>
		<div>
			<# if ( settings.show_postponed_status_preview && settings.show_status ) { #>
				<div class="<?php echo esc_attr( $this->get_widget_class() ); ?> ">
					<div <?php tribe_classes( $this->get_status_label_class(), $this->get_status_class( 'postponed' ) ); ?>><?php echo esc_html( $postponed_label ); ?></div>
					<div class="<?php echo esc_attr( $this->get_status_description_class() ); ?>">This event has been postponed.</div>
				</div>
				<br />
			<# } #>
			<# if ( settings.show_canceled_status_preview && settings.show_status ) { #>
			<div class="<?php echo esc_attr( $this->get_widget_class() ); ?>">
				<div <?php tribe_classes( $this->get_status_label_class(), $this->get_status_class( 'canceled' ) ); ?>"><?php echo esc_html( $canceled_label ); ?></div>
				<div class="<?php echo esc_attr( $this->get_status_description_class() ); ?>">This event has been canceled.</div>
			</div>
			<# } #>
		</div>
		<?php
	}

	/**
	 * Register controls for the widget.
	 *
	 * @since TBD
	 */
	protected function register_controls() {
		// Content tab.
		$this->content_panel();
		// Style tab.
		$this->style_panel();
	}

	/**
	 * Add content controls for the widget.
	 *
	 * @since TBD
	 */
	protected function content_panel() {
		$this->content_options();
		$this->preview_options();
	}

	/**
	 * Add styling controls for the widget.
	 *
	 * @since TBD
	 */
	protected function style_panel() {
		$this->passed_label_styling();

		$this->status_label_styling();

		$this->status_description_styling();

		$this->status_peripherals_styling();
	}

	/**
	 * Add controls for text content of the event status widget.
	 *
	 * @since TBD
	 */
	protected function content_options(): void {
		$this->start_controls_section(
			'content_section_title',
			[
				'label' => esc_html__( 'Content', 'the-events-calendar' ),
			]
		);

		$this->add_control(
			'content_notice',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__(
					'The following toggles control front-end display of the stati. They also affect the preview shown here.',
					'the-events-calendar'
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_shared_control(
			'show',
			[
				'id'      => 'show_passed',
				'label'   => esc_html__( 'Show Event Passed', 'the-events-calendar' ),
				'default' => 'no',
			]
		);

		$this->add_shared_control(
			'show',
			[
				'id'      => 'show_status',
				'label'   => esc_html__( 'Show Event Status', 'the-events-calendar' ),
				'default' => 'no',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Add controls for preview of the event status widget.
	 *
	 * @since TBD
	 */
	protected function preview_options() {
		$this->start_controls_section(
			'preview_section_title',
			[
				'label' => esc_html__( 'Preview Controls', 'the-events-calendar' ),
			]
		);

		$this->add_control(
			'preview_notice',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__(
					'The following toggles are for preview purposes only. They allow you to see a generic preview of what each status would look like, as if it applied.',
					'the-events-calendar'
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'show_passed_status_preview',
			[
				'label'     => esc_html__( 'Preview Passed', 'the-events-calendar' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'the-events-calendar' ),
				'label_off' => esc_html__( 'No', 'the-events-calendar' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_postponed_status_preview',
			[
				'label'     => esc_html__( 'Preview Postponed', 'the-events-calendar' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'the-events-calendar' ),
				'label_off' => esc_html__( 'No', 'the-events-calendar' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_canceled_status_preview',
			[
				'label'     => esc_html__( 'Preview Canceled', 'the-events-calendar' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'the-events-calendar' ),
				'label_off' => esc_html__( 'No', 'the-events-calendar' ),
				'default'   => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Add controls for text styling of the event passed label.
	 *
	 * @since TBD
	 */
	protected function passed_label_styling() {
		$this->start_controls_section(
			'passed_label_styling_section_title',
			[
				'label' => esc_html__( 'Passed Label', 'the-events-calendar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_shared_control(
			'typography',
			[
				'prefix'   => 'passed',
				'selector' => '{{WRAPPER}} .' . $this->get_passed_label_class(),
			]
		);

		$this->add_shared_control(
			'alignment',
			[
				'id'        => 'align_passed',
				'selectors' => [ '{{WRAPPER}} .' . $this->get_passed_label_class() ],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Add controls for text styling of the event status label.
	 *
	 * @since TBD
	 */
	protected function status_label_styling() {
		$this->start_controls_section(
			'status_label_styling_section_title',
			[
				'label' => esc_html__( 'Status Label', 'the-events-calendar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_shared_control(
			'typography',
			[
				'prefix'   => 'status',
				'selector' => '{{WRAPPER}} .' . $this->get_status_label_class(),
			]
		);

		$this->add_shared_control(
			'alignment',
			[
				'id'        => 'align_status',
				'selectors' => [ '{{WRAPPER}} .' . $this->get_status_label_class() ],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Add controls for text styling of the event status status.
	 *
	 * @since TBD
	 */
	protected function status_description_styling() {
		$this->start_controls_section(
			'status_description_styling_section_title',
			[
				'label' => esc_html__( 'Status Description', 'the-events-calendar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'status_description_color',
			[
				'label'     => esc_html__( 'Text Color', 'the-events-calendar' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .' . $this->get_status_description_class() => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'status_description_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} ' . $this->get_status_description_class(),
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'     => 'status_description_text_stroke',
				'selector' => '{{WRAPPER}} ' . $this->get_status_description_class(),
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'status_description_text_shadow',
				'selector' => '{{WRAPPER}} ' . $this->get_status_description_class(),
			]
		);

		$this->add_control(
			'status_description_blend_mode',
			[
				'label'     => esc_html__( 'Blend Mode', 'the-events-calendar' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''            => esc_html__( 'Normal', 'the-events-calendar' ),
					'multiply'    => esc_html__( 'Multiply', 'the-events-calendar' ),
					'screen'      => esc_html__( 'Screen', 'the-events-calendar' ),
					'overlay'     => esc_html__( 'Overlay', 'the-events-calendar' ),
					'darken'      => esc_html__( 'Darken', 'the-events-calendar' ),
					'lighten'     => esc_html__( 'Lighten', 'the-events-calendar' ),
					'color-dodge' => esc_html__( 'Color Dodge', 'the-events-calendar' ),
					'saturation'  => esc_html__( 'Saturation', 'the-events-calendar' ),
					'color'       => esc_html__( 'Color', 'the-events-calendar' ),
					'difference'  => esc_html__( 'Difference', 'the-events-calendar' ),
					'exclusion'   => esc_html__( 'Exclusion', 'the-events-calendar' ),
					'hue'         => esc_html__( 'Hue', 'the-events-calendar' ),
					'luminosity'  => esc_html__( 'Luminosity', 'the-events-calendar' ),
				],
				'selectors' => [
					'{{WRAPPER}} ' . $this->get_status_description_class() => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Add controls for text styling of the event status peripherals.
	 *
	 * @since TBD
	 */
	protected function status_peripherals_styling() {
		$this->start_controls_section(
			'status_peripherals_styling_section_title',
			[
				'label' => esc_html__( 'Status Peripherals', 'the-events-calendar' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'status_peripherals_main_border_color',
			[
				'label'     => esc_html__( 'Main Border Color', 'the-events-calendar' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#da394d',
				'selectors' => [
					'{{WRAPPER}} .' . $this->get_widget_class() => 'border: 1px solid {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'status_peripherals_border_left_color',
			[
				'label'     => esc_html__( 'Left Border Color', 'the-events-calendar' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .' . $this->get_widget_class() => 'border-left: 4px solid {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}
}
