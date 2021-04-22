<?php
/**
* Html Code For Settings Tab
*/
?>
<div class="tab_inner_container">
	<form method="post" id="mfata_settings_form" action="" enctype="multipart/form-data">
		<?php #nonce ?>
		<div class="outer_form_table">		
			<table class="form-table heading-table">
				<tbody>
					<tr>
						<td>
							<label><?php esc_html_e( 'Migrate tracking numbers from AfterShip to AST for the last', 'migrate-from-aftership-to-ast' ); ?>
							<input type="number" min='1' value="30" name="migration_data_duration" class="migration_data_duration">	
							<?php esc_html_e( 'days', 'migrate-from-aftership-to-ast' ); ?>
							</label>														
						</td>
						<td>
							<div class="settings_submit">								
								<button name="save" class="button-primary woocommerce-save-button btn_settings btn_large" type="submit" value="Save changes"><?php esc_html_e( 'Migrate', 'migrate-from-aftership-to-ast' ); ?></button>
								<div class="spinner"></div>
								<?php wp_nonce_field( 'mfata_settings_tab', 'mfata_settings_tab_nonce' ); ?>
								<input type="hidden" name="action" value="mfata_migrate_data">
							</div>
						</td>
					</tr>					
				</tbody>
			</table>			
		</div>
	</form>				
</div>	
