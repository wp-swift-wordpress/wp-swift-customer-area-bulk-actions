<?php
function wp_swift_hook_javascript() {
	/**
	 * Create the ajax nonce and url
	 */
    $bulk_action_ajax = array(
        // URL to wp-admin/admin-ajax.php to process the request
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        // generate a nonce with a unique ID so that you can check it later when an AJAX request is sent
        'security' => wp_create_nonce( 'bulk-action-nonce' ),
        'action' => 'wp_swift_submit_bulk_action',
    );
?>
<script>
var BulkActionAjax = <?php echo json_encode($bulk_action_ajax); ?>; 
jQuery(document).ready(function($) {
	var options = [{value:'publish-post', text:'Publish'},  {value:'publish-post-notify', text:'Publish and Notify'}];
	var option;
	var optionTop;
	var optionBottom;	
	var $apply = $('#doaction');
	var $bulkActionsTop = $('#bulk-action-selector-top');
	var $bulkActionsBottom = $('#bulk-action-selector-bottom');

	for (var i = 0; i < options.length; i++) {
		$bulkActionsTop.append($('<option>', options[i]));
		$bulkActionsBottom.append($('<option>', options[i]));
	}

	$apply.click(function( event ) {
	  	optionTop = $bulkActionsTop.find(":selected").val();
	  	optionBottom = $bulkActionsBottom.find(":selected").val();
		var postIDs = $('input[name="posts[]"]:checked').map(function(){
		   	return $(this).val();
		}).get();

		if ( postIDs.length === 0 && optionTop === "-1" && optionBottom === "-1" ) {
			event.preventDefault();
			alert( "You must choose a bulk action and select at least one post before proceeding!" );
			return false; 
		} 
		else if ( postIDs.length > 0 ) {
			if (optionTop === 'publish-post' || optionTop === 'publish-post-notify' || optionBottom === 'publish-post' || optionBottom === 'publish-post-notify') {
				event.preventDefault();

				if (optionTop !== "-1") {
					option = optionTop;
				}
				else {
					option = optionBottom;
				}
				
				if (option === "publish-post-notify") {
					if (! confirm( "This action will send an email to all associated users with a message that this file is available." 
						 			 + "\n\n" + "Do you wish to continue?") ) {
						event.preventDefault();
						return false; 
					} 		
				}

				BulkActionAjax.post_ids = postIDs;
				BulkActionAjax.option = option;
				$.post( BulkActionAjax.ajaxurl, BulkActionAjax, function(response) {
					var serverResponse = JSON.parse(response);
					if (serverResponse.updated.length > 0) {
						// Reload page
						location.reload();					
					}
					if (serverResponse.alert) {
						alert( serverResponse.alert );
					}
				});						

				return false; 
			}
			else if (optionTop === "-1" && optionBottom === "-1" ) {
				event.preventDefault();
				alert( "Please select a bulk action!" );
				return false; 				
			}
			else {
				return true;
			}				
		}
		else {
			event.preventDefault();
			alert( "Please select at least one post!" );
			return false; 
		}
		return true;  
	});
});
//$( "h1" ).after( admin_notice('<p><strong>Settings saved.</strong></p>') );
function admin_notice( $message, $type = 'notice-success' ) {
	var html = '<div class="notice ' + $type + ' is-dismissible"> ';
	html += '	' + $message;
	html += '	<button type="button" class="notice-dismiss">';
	html += '		<span class="screen-reader-text">Dismiss this notice.</span>';
	html += '	</button>';
	html += '</div>	';
	return html;
}
</script>
<?php
}
add_action('admin_head', 'wp_swift_hook_javascript');