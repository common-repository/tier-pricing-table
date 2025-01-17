<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	
/**
 * Available variables
 *
 * @var string $accountUrl
 * @var string $contactUsUrl
 */
?>
<div class="tpt-alert">
	
	<div class="tpt-alert__text">
		<div class="tpt-alert__inner">
			<?php
				esc_html_e( 'You are running premium version of the plugin!', 'tier-pricing-table' );
			?>
		</div>
	</div>
	
	<div class="tpt-alert__buttons">
		<div class="tpt-alert__inner">
			<a class="tpt-button tpt-button--accent" href="<?php echo esc_attr( $accountUrl ); ?>">
				<?php esc_html_e( 'My Account', 'tier-pricing-table' ); ?>
			</a>
			<a class="tpt-button tpt-button--default" href="<?php echo esc_attr( $contactUsUrl ); ?>">
				<?php esc_html_e( 'Contact us', 'tier-pricing-table' ); ?>
			</a>
		</div>
	</div>
</div>