<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
}

/**
 * Copyright (c) 2017 Eolia Consulting
 *
 * @author     Ronan Pozzi <r.pozzi@eolia-consulting.com>
 * @date       2017
 * @copyright  Eolia Software (http://www.eolia-consulting.com)
 * @licence    GNU
 *
 * @file       file.input.php
 * @package    demo
 * @version    1.0.0
 */

use Eolia\Controllers\FieldController;

/** @var FieldController $field */
$options = get_option( 'eolia-app' );
?>
<div class="eolia_form-row eolia_form-row--<?php echo $field->get_component() ?><?php echo ! $field->is_mobile() ? ' eolia_form-row--mobile-hidden' : null ?>"
     data-field-id="<?php echo $field->get_id() ?>">
	<div class="eolia_form-group<?php echo $field->get_required() ? ' eolia_form-group--required' : null ?>"
	     data-field-id="<?php echo $field->get_id() ?>">
		<p class="eolia_field_label<?php echo $field->get_required() ? ' eolia_field_label--required' : null ?>"><?php echo $field->get_label() ?></p>
		<div class="eolia_input-group eolia_input-group--file">
			<label class="eolia_input eolia_input--button" for="<?php echo $field->get_id() ?>">
				<?php echo apply_filters( "eolia_filter_form_field_{$field->get_id()}_upload_button",
					_x( 'Browse...', 'form uploaded', 'eolia-app' ) ) ?>
			</label>
			<input type="file" hidden
			       class="hidden-file" <?php echo FieldController::formatAttributes( $field->get_field_attributes() ) ?>/>
			<input type="text" name="label_file_<?php echo $field->get_id() ?>" class="eolia_input eolia_input--filelabel" readonly>
		</div>
	</div>
	<?php if ( $options['is_applygoogle'] ) : ?>
		<?php
		wp_enqueue_script( 'google-api' );
		wp_enqueue_script( 'google-client-api' );
		?>
		<a href="#" class="eolia_input eolia_input--button upload_with upload_with--googledrive"
		   data-ga="Offer::Upload::Google Drive" data-replace="<?php echo $field->get_id(); ?>">
			<svg class="fileUpload-picto fileUpload-picto--google" version="1.1" viewBox="0 0 550.801 550.801">
				<path
					d="M538.051,339.15L362.1,33.15H188.7l0,0l175.951,306H538.051z M216.75,364.65l-86.7,153H464.1l86.701-153H216.75z M165.75,71.4L0,364.65l86.7,153L255,224.4L165.75,71.4z"></path>
			</svg>
			<span><?php _ex( 'Googledrive', 'upload', 'eolia-app' ); ?></span></a>
	<?php endif ?>
	<?php if ( $options['is_applydropbox'] ) : ?>
		<?php wp_enqueue_script( 'dropbox-api' ); ?>
		<script type="text/javascript" src="//www.dropbox.com/static/api/2/dropins.js" id="dropboxjs"
		        data-app-key="<?php echo $options['dropbox_app_key'] ?>"></script>
		<a href="#" class="eolia_input eolia_input--button upload_with upload_with--dropbox"
		   data-ga="Offer::Upload::Dropbox" data-replace="<?php echo $field->get_id(); ?>">
			<svg class="fileUpload-picto fileUpload-picto--dropbox" version="1.1" viewBox="0 0 26 26">
				<polygon
					points="18.139,20.061 13,16.195 7.967,20.058 5.042,18.343 5.042,20.214 13,25.475 20.977,20.25 20.977,18.374 "/>
				<polygon points="26,5.38 18.326,0.525 13,4.905 20.965,9.535"/>
				<polygon points="0,13.81 7.896,18.44 13,14.522 5.438,9.712"/>
				<polygon points="7.896,0.525 0,5.618 5.438,9.712 13,4.905"/>
				<polygon points="13,14.522 18.207,18.44 26,13.81 20.965,9.535"/>
			</svg>
			<span><?php _ex( 'Dropbox', 'upload', 'eolia-app' ); ?></span></a>
	<?php endif ?>
	<?php if ( 'pj1' === $field->get_id() ): ?>
		<?php if ( $options['is_applyviadeo'] ) : ?>
		<?php $_SESSION['viadeo_state'] = substr( sha1( rand() ), 0, 20 ); ?>
		<a title="<?php printf( _x( 'Apply with %s',
			'apply_with',
			'eolia-app' ),
			'viadeo' ); ?>" href="#" class="eolia_input eolia_input--button apply_with apply_with--viadeo"
		   data-ga="Offer::Apply With::Viadeo" data-replace="<?php $field->get_id() ?>"
		   data-url-signin="https://partners.viadeo.com/oauth/authorize?client_id=<?php echo $options['viadeo_id'] ?>>&redirect_uri=<?php echo urlencode( plugins_url( 'public/viadeo_response.php',
			   'eolia-app/eolia-app.php' ) ) ?>&response_type=code&scope=api&state=<?php echo $_SESSION['viadeo_state'] ?>"
		   data-url-check-auth="<?php echo plugins_url( 'public/viadeo_response.php', 'eolia-app/eolia-app.php' ) ?>">
			<svg class="fileUpload-picto fileUpload-picto--viadeo" version="1.1" viewBox="0 0 23 32">
				<path
					d="M18.75 19.75q0 3.839-2.625 6.679-2.643 2.875-6.75 2.875-4.143 0-6.75-2.875-2.625-2.839-2.625-6.679 0-2.625 1.214-4.83t3.375-3.509 4.786-1.304q1.714 0 3.25 0.554-0.571 1.107-0.696 2.25-1.179-0.5-2.554-0.5-2.982 0-5.009 2.196t-2.027 5.196q0 3.036 2.009 5.152t5.027 2.116 5.018-2.116 2-5.152q0-1.589-0.571-2.964 1.179-0.232 2.196-0.875 0.732 1.75 0.732 3.786zM15.107 16.375q0 3.429-1.42 6.161t-4.259 4.518l-0.25 0.018q-0.518 0-1.107-0.089 1.482-0.571 2.616-1.83t1.777-2.759 1.045-3.375 0.536-3.438 0.134-3.188q0-1.232-0.054-1.839 0.982 2.857 0.982 5.821zM14.125 10.518v0.036q-1.304-3.821-3.679-7.857 1.571 1.054 2.545 3.33t1.134 4.491zM18.482 14.143q-1.482 0-2.857-1.339 3.893-2.143 5.179-4.411 0.339-0.661 0.375-1-0.75 1.679-2.491 2.973t-3.652 1.741q-0.625-0.964-0.625-2.018 0-0.661 0.304-1.411t0.768-1.214q0.821-0.786 2.804-1.321 1.054-0.286 1.893-1.045t1.321-1.795q1.321 1.875 1.321 4.518 0 1.946-0.429 3.036-0.571 1.375-1.58 2.33t-2.33 0.955z"></path>
			</svg>
			<span><?php printf( _x( 'Apply with %s', 'apply_with', 'eolia-app' ), 'Viadeo' ) ?></span></a>
	<?php endif ?>
	<?php if ( $options['is_applylinkedin'] ) : ?>
	<?php $_SESSION['linkedin_state'] = substr( sha1( rand() ), 0, 20 ); ?>
		<a title="<?php printf( _x( 'Apply with %s',
			'apply_with',
			'eolia-app' ),
			'LinkedIn' ) ?>" href="#" class="eolia_input eolia_input--button apply_with apply_with--linkedin"
		   data-ga="Offer::Apply With::Linkedin"
		   data-url-signin="https:/www.linkedin.com/oauth/v2/authorization?client_id=<?php echo $options['linkedin_id'] ?>&redirect_uri=<?php echo urlencode( plugins_url( 'public/linkedin_response.php',
			   'eolia-app/eolia-app.php' ) ) ?>&state=<?php echo $_SESSION['linkedin_state'] ?>&response_type=code&scope=r_basicprofile%20r_emailaddress"
		   data-url-check-auth="<?php echo plugins_url('public/linkedin_response.php', 'eolia-app/eolia-app.php') ?>">
			<svg class="fileUpload-picto fileUpload-picto--linkedin" version="1.1" viewBox="0 0 23 32">
				<path
					d="M29 0h-26c-1.65 0-3 1.35-3 3v26c0 1.65 1.35 3 3 3h26c1.65 0 3-1.35 3-3v-26c0-1.65-1.35-3-3-3zM12 26h-4v-14h4v14zM10 10c-1.106 0-2-0.894-2-2s0.894-2 2-2c1.106 0 2 0.894 2 2s-0.894 2-2 2zM26 26h-4v-8c0-1.106-0.894-2-2-2s-2 0.894-2 2v8h-4v-14h4v2.481c0.825-1.131 2.087-2.481 3.5-2.481 2.488 0 4.5 2.238 4.5 5v9z"></path>
			</svg>
			<span><?php printf( _x( 'Apply with %s', 'apply_with', 'eolia-app' ), 'LinkedIn' ) ?></span></a>
	<?php endif ?>
	<?php if ( is_singular( 'job' ) && $options['is_applyindeed'] ) : ?>
	<?php
	$_SESSION['indeed_status'] = substr( sha1( rand() ), 0, 20 );
	$post_url                  = get_bloginfo( 'url' ) . '?' . http_build_query( array(
			'action' => 'indeed',
			'id'     => session_id(),
		) );
	?>
		<div style="display: none">
			<?php $job = eolia_get_job() ?>
			<span class="indeed-apply-widget"
			      data-indeed-apply-jobUrl="<?php echo get_the_permalink(); ?>"
			      data-indeed-apply-jobId="<?php echo $job->get_business_unit() . '_' . $job->get_id() . '_' . $job->get_ref() ?>"
			      data-indeed-apply-jobTitle="<?php echo $job->get_title() . ' - ' . $job->get_ref() ?>"
			      data-indeed-apply-jobCompanyName="<?php echo get_bloginfo( 'name' ) ?>"
			      data-indeed-apply-coverletter="hidden" data-indeed-apply-resume="optional"
			      data-indeed-apply-name="firstlastname"
			      data-indeed-apply-onapplied="onSuccessIndeed"
			      data-indeed-apply-jobLocation="<?php echo $job->get_additionnal_field('saisie2') ?>"
			      data-indeed-apply-apiToken="<?php echo $options['indeed_token'] ?>"
			      data-indeed-apply-postUrl="<?php echo esc_url( add_query_arg( _x( 'apply', 'slug', 'eolia-app' ), '', get_the_permalink() ) )?>">
		</span>
		</div>
		<script>(function (d, s, id) {
				var js, iajs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {
					return;
				}
				js = d.createElement(s);
				js.id = id;
				js.async = true;
				js.src = 'https://apply.indeed.com/indeedapply/static/scripts/app/bootstrap.js';
				iajs.parentNode.insertBefore(js, iajs);
			}(document, 'script', 'indeed-apply-js'));
		</script>
		<a href="#" class="eolia_input eolia_input--button apply_with apply_with--indeed"
		   data-ga="Offer::Apply With::Indeed"
		   data-replace="<?php echo $field->get_id() ?>">
			<svg class="fileUpload-picto fileUpload-picto--indeed" version="1.1" viewBox="0 0 21.5 32">
				<path
					d="M10.2,28.7V17.1c0.3,0,0.7,0,1,0c1.6,0,3.1-0.4,4.3-1.2v12.8c0,1.1-0.3,1.9-0.7,2.4c-0.5,0.5-1.1,0.8-1.9,0.8c-0.8,0-1.4-0.3-1.9-0.8C10.4,30.6,10.2,29.8,10.2,28.7L10.2,28.7z"/>
				<g>
					<path
						d="M10.2,0.8c3.3-1.2,7.1-1.1,9.9,1.3c0.5,0.5,1.1,1.1,1.4,1.8c0.3,0.9-1-0.1-1.2-0.2c-0.9-0.6-1.8-1.1-2.9-1.5 C11.9,0.5,6.6,3.6,3.4,8.4c-1.4,2.1-2.3,4.4-3,6.8c-0.1,0.3-0.1,0.6-0.3,0.9C-0.1,16.4,0,15.4,0,15.3c0.1-1,0.3-2,0.6-3 C2.1,7.1,5.4,2.8,10.2,0.8z"/>
					<path
						d="M14.5,13.8c-2,1-4.4,0.2-5.4-1.8c-1-2-0.2-4.4,1.8-5.4c2-1,4.4-0.2,5.4,1.8C17.3,10.3,16.5,12.8,14.5,13.8z"/>
				</g>
			</svg>
			<span><?php printf( _x( 'Apply with %s', 'apply_with', 'eolia-app' ), 'Indeed' ) ?></span></a>
	<?php endif; ?>
	<?php endif; ?>
</div>

