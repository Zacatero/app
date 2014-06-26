<?php

$messages = [];

$messages[ 'en' ] = [
	'wikia-interactive-maps-title' => 'Interactive Maps',
	'wikia-interactive-maps-create-a-map' => 'Create a Map',
	'wikia-interactive-maps-no-maps' => 'No maps found. Create new map now.',

	'wikia-interactive-maps-map-status-done' => 'Ready to use',
	'wikia-interactive-maps-map-status-processing' => 'Processing...',

	'wikia-interactive-maps-parser-tag-error-no-require-parameters' => 'Wikia Interactive Maps error occurred: no parameters passed to the tag. The only required parameter is map-id parameter. Make sure it\'s set, please.',
	'wikia-interactive-maps-parser-tag-error-invalid-map-id' => 'Wikia Interactive Maps error occurred: invalid map id. Please make sure your map-id parameter is an integer number.',
	'wikia-interactive-maps-parser-tag-error-invalid-longitude' => 'Wikia Interactive Maps error occurred: invalid lon. Please make sure your longitude parameter is a number or remove it to set it to default value.',
	'wikia-interactive-maps-parser-tag-error-invalid-latitude' => 'Wikia Interactive Maps error occurred: invalid lat. Please make sure your latitude parameter is a number or remove it to set it to default value.',
	'wikia-interactive-maps-parser-tag-error-invalid-zoom' => 'Wikia Interactive Maps error occurred: invalid zoom. Please make sure your zoom parameter is an integer number or remove it to set it to default value.',
	'wikia-interactive-maps-parser-tag-error-invalid-width' => 'Wikia Interactive Maps error occurred: invalid width. Please make sure your width parameter is an integer number or remove it to set it to default value.',
	'wikia-interactive-maps-parser-tag-error-invalid-height' => 'Wikia Interactive Maps error occurred: invalid height. Please make sure your height parameter is an integer number or remove it to set it to default value.',
	'wikia-interactive-maps-parser-tag-error-min-latitude' => 'Wikia Interactive Maps error occurred: invalid lat. Please make sure your latitude is higher then $min.',
	'wikia-interactive-maps-parser-tag-error-max-latitude' => 'Wikia Interactive Maps error occurred: invalid lat. Please make sure your latitude is lower then $max.',
	'wikia-interactive-maps-parser-tag-error-min-longitude' => 'Wikia Interactive Maps error occurred: invalid lon. Please make sure your longitude is higher then $min.',
	'wikia-interactive-maps-parser-tag-error-max-longitude' => 'Wikia Interactive Maps error occurred: invalid lon. Please make sure your longitude is lower then $max.',
	'wikia-interactive-maps-parser-tag-error-min-zoom' => 'Wikia Interactive Maps error occurred: invalid zoom. Please make sure your zoom parameter is set to $min or higher.',
	'wikia-interactive-maps-parser-tag-error-max-zoom' => 'Wikia Interactive Maps error occurred: invalid zoom. Please make sure your zoom parameter is to $max or lower.',
	'wikia-interactive-maps-parser-tag-error-min-width' => 'Wikia Interactive Maps error occurred: invalid width. Minimum supported with is $min.',
	'wikia-interactive-maps-parser-tag-error-max-width' => 'Wikia Interactive Maps error occurred: invalid width. Maximum supported with is $max.',
	'wikia-interactive-maps-parser-tag-error-min-height' => 'Wikia Interactive Maps error occurred: invalid height. Minimum supported height is $min.',
	'wikia-interactive-maps-parser-tag-error-max-height' => 'Wikia Interactive Maps error occurred: invalid height. Maximum supported height is $max.',
	'wikia-interactive-maps-parser-tag-error-no-map-found' => 'Wikia Interactive Maps error occurred: Map not found.',

	'wikia-interactive-maps-map-placeholder-error' => 'Unexpected error has occurred. Please contact us if it happens again.',
	'wikia-interactive-maps-service-error' => 'Oops, we have issues with our maps service. If it repeats [[Special:Contact|contact us]], please.',

	'wikia-interactive-maps-sort-newest-to-oldest' => 'Newest to Oldest',
	'wikia-interactive-maps-sort-alphabetical' => 'Alphabetical',
	'wikia-interactive-maps-sort-recently-updated' => 'Recently updated',

	'wikia-interactive-maps-map-not-found-error' => 'Map not found',

	'wikia-interactive-maps-create-map-header' => 'Create a Map',
	'wikia-interactive-maps-create-map-next-btn' => 'Next',
	'wikia-interactive-maps-create-map-back-btn' => 'Back',
	'wikia-interactive-maps-create-map-choose-type-geo' => 'Real Map',
	'wikia-interactive-maps-create-map-choose-type-custom' => 'Custom Map',
	'wikia-interactive-maps-create-map-title-placeholder' => 'ex. Weapon Location in Skyrim',
	'wikia-interactive-maps-create-map-browse-tile-set' => 'Browse existing tile sets',
	'wikia-interactive-maps-create-map-add-poi-category' => 'Add Another Pin Type',
	'wikia-interactive-maps-create-map-delete-poi-category' => 'Delete',
	'wikia-interactive-maps-create-map-poi-category-name-placeholder' => 'Pin Type Title',
	'wikia-interactive-maps-create-map-poi-category-select-category' => 'Parent Category',
	'wikia-interactive-maps-create-map-poi-category-form-error' => 'Please fill all fields',
	'wikia-interactive-maps-create-map-search-tile-set-placeholder' => 'Search',
	'wikia-interactive-maps-create-map-upload-file' => 'Click to upload custom template',
	'wikia-interactive-maps-create-map-choose-tile-set-tip' => 'Select an existing map template or upload your own to get started.',
	'wikia-interactive-maps-create-map-no-tile-set-found' => 'Sorry, no templates found.',
	'wikia-interactive-maps-create-map-clear-tile-set-search' => 'Clear search filter',
	'wikia-interactive-maps-create-map-choose-type-tip' => 'Get started choosing a real map (ex. NYC) or custom (ex. Westeros).',
	'wikia-interactive-maps-create-map-choose-type-tip-link' => 'Learn more',
	'wikia-interactive-maps-create-map-title-label' => 'Name the map',
	'wikia-interactive-maps-create-map-tile-set-title-label' => 'Name the template',
	'wikia-interactive-maps-create-map-tile-set-title-placeholder' => 'Name the image so others can re-use it for their Maps (ex. Westeros)',
	'wikia-interactive-maps-create-map-tile-set-image-preview-alt' => 'Template image preview',
	'wikia-interactive-maps-create-map-validation-error-map-title' => 'Error: Name of the map must be set',
	'wikia-interactive-maps-create-map-validation-error-tile-set-title' => 'Error: Name of the template must be set',

	'wikia-interactive-maps-edit-poi-header-add-poi' => 'Add Pin Point',
	'wikia-interactive-maps-edit-poi-header-edit-poi' => 'Edit Pin Point',
	'wikia-interactive-maps-edit-poi-save' => 'Save',
	'wikia-interactive-maps-edit-poi-cancel' => 'Cancel',
	'wikia-interactive-maps-edit-poi-delete' => 'Delete',
	'wikia-interactive-maps-edit-poi-name-placeholder' => 'Title',
	'wikia-interactive-maps-edit-poi-article-placeholder' => 'Associated Article',
	'wikia-interactive-maps-edit-poi-category-placeholder' => 'Select a Pin Type',
	'wikia-interactive-maps-edit-poi-description-placeholder' => 'Add a Description',
	'wikia-interactive-maps-edit-poi-error-name' => 'Name must be set',
	'wikia-interactive-maps-edit-poi-error-poi-category-id' => 'Pin type must be set',
	'wikia-interactive-maps-embed-map-code-header' => 'Embed Map',
	'wikia-interactive-maps-embed-map-code-info' => 'Use the embed code below to include the map on your personal site.',
	'wikia-interactive-maps-embed-map-code-size-label' => 'Map Size',

	'wikia-interactive-maps-create-map-bad-request-error' => 'Neither of required parameters was provided',

	'wikia-interactive-maps-image-uploads-disabled' => 'File uploads are currently disabled on your wiki. Please try again later.',
	'wikia-interactive-maps-image-uploads-error' => 'There was an error while uploading the image. If it repeats [[Special:Contact|contact us]], please.',
	'wikia-interactive-maps-image-uploads-warning' => 'There were some issues while uploading the image. If it repeats [[Special:Contact|contact us]], please.',
	'wikia-interactive-maps-image-uploads-error-file-too-large' => 'The file you tried to upload is too big. Maximum image size is $1 MB',
	'wikia-interactive-maps-image-uploads-error-empty-file' => 'The file you tried to upload is empty',
	'wikia-interactive-maps-image-uploads-error-bad-type' => 'The file you tried to upload is not an image',
	'wikia-interactive-maps-image-uploads-error-poi-category-marker-too-small' => 'Error: The image you uploaded was too small. Please use an image that is at least $1 x $1 pixels.',

	'wikia-interactive-maps-create-map-error-invalid-map-title' => 'Error: You must title the map before proceeding.',

	'wikia-interactive-maps-actions' => 'Actions',
	'wikia-interactive-maps-delete-map' => 'Delete map',
	'wikia-interactive-maps-delete-map-client-title' => 'Delete map',
	'wikia-interactive-maps-delete-map-client-prompt' => 'Are you sure to delete the map?',
	'wikia-interactive-maps-delete-map-client-confirm-button' => 'Delete Map',
	'wikia-interactive-maps-delete-map-client-cancel-button' => 'Cancel',
	'wikia-interactive-maps-delete-map-client-error' => 'There was an error while deleting a map.',
	'wikia-interactive-maps-delete-map-success' => 'Map was succesfuly deleted!',

	'wikia-interactive-maps-create-poi-categories-error' => 'Unfortunately, we could not create all pin types. Some of them might have been created, though. Check which are missing and try adding them again, please.',

	'wikia-interactive-maps-log-name' => 'Maps',
	'wikia-interactive-maps-log-description' => 'This is a log of maps actions',
	'wikia-interactive-maps-create-map-log-entry' => 'created new map [[$1]]',
	'wikia-interactive-maps-update-map-log-entry' => 'updated map [[$1]]',
	'wikia-interactive-maps-delete-map-log-entry' => 'deleted map [[$1]]',
	'wikia-interactive-maps-create-pin-type-log-entry' => 'created new pin type for [[$1]]',
	'wikia-interactive-maps-update-pin-type-log-entry' => 'updated pin type for [[$1]]',
	'wikia-interactive-maps-delete-pin-type-log-entry' => 'deleted pin type for [[$1]]',
	'wikia-interactive-maps-create-pin-log-entry' => 'created new pin for [[$1]]',
	'wikia-interactive-maps-update-pin-log-entry' => 'updated pin for [[$1]]',
	'wikia-interactive-maps-delete-pin-log-entry' => 'deleted pin for [[$1]]',
];

$messages[ 'qqq' ] = [
	'wikia-interactive-maps-title' => 'Interactive maps special page title',
	'wikia-interactive-maps-create-a-map' => 'Label for create new map button',
	'wikia-interactive-maps-no-maps' => 'Shown when there are no maps created for this wiki',

	'wikia-interactive-maps-map-status-done' => 'Message which informs about map tiles processing status; not visible for a user',
	'wikia-interactive-maps-map-status-processing' => 'Message which informs about map tiles processing status; not visible for a user',

	'wikia-interactive-maps-parser-tag-error-no-require-parameters' => 'Interactive maps error after try of parsing wikitext tag; one of the required parameters is not set',
	'wikia-interactive-maps-parser-tag-error-invalid-map-id' => 'Interactive maps error after try of parsing wikitext tag; the map-id is not passed or is not a valid number',
	'wikia-interactive-maps-parser-tag-error-invalid-latitude' => 'Interactive maps error after try of parsing wikitext tag; an invalid latitude value has been passed',
	'wikia-interactive-maps-parser-tag-error-invalid-longitude' => 'Interactive maps error after try of parsing wikitext tag; an invalid longitude has been passed',
	'wikia-interactive-maps-parser-tag-error-invalid-zoom' => 'Interactive maps error after try of parsing wikitext tag; an invalid zoom has been passed',
	'wikia-interactive-maps-parser-tag-error-invalid-width' => 'Interactive maps error after try of parsing wikitext tag; an invalid width has been passed',
	'wikia-interactive-maps-parser-tag-error-invalid-height' => 'Interactive maps error after try of parsing wikitext tag; an invalid height has been passed',
	'wikia-interactive-maps-parser-tag-error-min-latitude' => 'Interactive maps error after try of parsing wikitext tag; an invalid latitude has been passed',
	'wikia-interactive-maps-parser-tag-error-max-latitude' => 'Interactive maps error after try of parsing wikitext tag; an invalid latitude has been passed',
	'wikia-interactive-maps-parser-tag-error-min-longitude' => 'Interactive maps error after try of parsing wikitext tag; an invalid longitude has been passed',
	'wikia-interactive-maps-parser-tag-error-max-longitude' => 'Interactive maps error after try of parsing wikitext tag; an invalid longitude has been passed',
	'wikia-interactive-maps-parser-tag-error-min-zoom' => 'Interactive maps error after try of parsing wikitext tag; an invalid zoom level has been passed',
	'wikia-interactive-maps-parser-tag-error-max-zoom' => 'Interactive maps error after try of parsing wikitext tag; an invalid zoom level has been passed',
	'wikia-interactive-maps-parser-tag-error-min-width' => 'Interactive maps error after try of parsing wikitext tag; value below minimum width has been passed',
	'wikia-interactive-maps-parser-tag-error-max-width' => 'Interactive maps error after try of parsing wikitext tag; value above maximum width has been passed',
	'wikia-interactive-maps-parser-tag-error-min-height' => 'Interactive maps error after try of parsing wikitext tag; value below minimum height has been passed',
	'wikia-interactive-maps-parser-tag-error-max-height' => 'Interactive maps error after try of parsing wikitext tag; value above maximum height has been passed',
	'wikia-interactive-maps-parser-tag-error-no-map-found' => 'Interactive maps error when map of given id doesn\'t exist',

	'wikia-interactive-maps-map-placeholder-error' => 'Interactive maps unexpected error which could happen during some rare situations such as file system dead',
	'wikia-interactive-maps-service-error' => 'An error message which appears in the creation map modal when our map service fails to create a map',

	'wikia-interactive-maps-sort-newest-to-oldest' => 'Ordering option shown in drop-down menu above map lists; when chosen orders map list from newest map to oldest',
	'wikia-interactive-maps-sort-alphabetical' => 'Ordering option shown in drop-down menu above map lists; when chosen orders map list in alphabetical order',
	'wikia-interactive-maps-sort-recently-updated' => 'Ordering option shown in drop-down menu above map lists; when chosen orders map list so the recently update maps are on the top',

	'wikia-interactive-maps-map-not-found-error' => 'Error message, shown when map is not found',

	'wikia-interactive-maps-create-map-header' => 'Header for create map modal',
	'wikia-interactive-maps-create-map-next-btn' => 'Next button for create map modal',
	'wikia-interactive-maps-create-map-back-btn' => 'Back button for create map modal',
	'wikia-interactive-maps-create-map-choose-type-geo' => 'Button for choosing real map type',
	'wikia-interactive-maps-create-map-choose-type-custom' => 'Button for choosing custom map type',
	'wikia-interactive-maps-create-map-title-placeholder' => 'Input placeholder for map title',
	'wikia-interactive-maps-create-map-browse-tile-set' => 'Link to browse tile set step in create map modal',
	'wikia-interactive-maps-create-map-add-poi-category' => 'Link for adding new blank input for Pin Type in edit/create Pin types UI',
	'wikia-interactive-maps-create-map-delete-poi-category' => 'Link title for deleting Pin Type in edit/create Pin types UI',
	'wikia-interactive-maps-create-map-poi-category-name-placeholder' => 'Input placeholder for pin types title',
	'wikia-interactive-maps-create-map-poi-category-select-category' => 'Label for no category selected',
	'wikia-interactive-maps-create-map-poi-category-form-error' => 'An error displayed to a user when pin type form is not valid',
	'wikia-interactive-maps-create-map-search-tile-set-placeholder' => 'Input placeholder for search field for tile sets',
	'wikia-interactive-maps-create-map-upload-file' => 'Link to trigger file upload for tile set',
	'wikia-interactive-maps-create-map-choose-tile-set-tip' => 'Tip for the user with information to select an existing map template or upload his own to get started creating a new map',
	'wikia-interactive-maps-create-map-no-tile-set-found' => 'Error message - no map templates found',
	'wikia-interactive-maps-create-map-clear-tile-set-search' => 'Button to clear search filter for tile set and revert to initial tile set list',
	'wikia-interactive-maps-create-map-choose-type-tip' => 'Tool tip about choose type screen',
	'wikia-interactive-maps-create-map-choose-type-tip-link' => 'Learn more link about choosing map type',
	'wikia-interactive-maps-create-map-title-label' => 'Label for map title input',
	'wikia-interactive-maps-create-map-tile-set-title-label' => 'Label for tile set title input',
	'wikia-interactive-maps-create-map-tile-set-title-placeholder' => 'Placeholder for tile set title input',
	'wikia-interactive-maps-create-map-tile-set-image-preview-alt' => 'Alt attribute fot template image preview',
	'wikia-interactive-maps-create-map-validation-error-map-title' => 'Error for empty map title',
	'wikia-interactive-maps-create-map-validation-error-tile-set-title' => 'Error for empty tile set title',

	'wikia-interactive-maps-edit-poi-header-add-poi' => 'Modal header for add pin to map',
	'wikia-interactive-maps-edit-poi-header-edit-poi' => 'Modal header for edit pin to map',
	'wikia-interactive-maps-edit-poi-save' => 'Modal button to save pin on map',
	'wikia-interactive-maps-edit-poi-cancel' => 'Modal button cancel action edit / add pin to map',
	'wikia-interactive-maps-edit-poi-delete' => 'Modal button - delete pin from map',
	'wikia-interactive-maps-edit-poi-name-placeholder' => 'Input placeholder for pin name in edit pin modal',
	'wikia-interactive-maps-edit-poi-article-placeholder' => 'Input placeholder for associated article in edit pin modal',
	'wikia-interactive-maps-edit-poi-category-placeholder' => 'Placeholder / default state option in select pin category',
	'wikia-interactive-maps-edit-poi-description-placeholder' => 'Textarea placeholder for pin description in edit pin modal',
	'wikia-interactive-maps-edit-poi-error-name' => 'Error message - missing pin name in add pin UI',
	'wikia-interactive-maps-edit-poi-error-poi-category-id' => 'Error message - pin type not set in add pin UI',
	'wikia-interactive-maps-embed-map-code-header' => 'Embed Map modal header - the title of "window" with embed map code',
	'wikia-interactive-maps-embed-map-code-info' => 'Information for the user to copy code below in order to embed map to personal site',
	'wikia-interactive-maps-embed-map-code-size-label' => 'Label for embed map size buttons',


	'wikia-interactive-maps-create-map-bad-request-error' => 'An API error message not visible for the user',

	'wikia-interactive-maps-image-uploads-disabled' => 'An error displayed to a user when files upload is disabled on a wikia',
	'wikia-interactive-maps-image-uploads-error' => 'An error displayed to a user when a file upload fails because of backend errors',
	'wikia-interactive-maps-image-uploads-warning' => 'An error displayed to a user when a file upload fails because of backend warnings',
	'wikia-interactive-maps-image-uploads-error-file-too-large' => 'An error displayed to a user when a file upload fails because of incorrect file size; the only parameter is maximum size of file in MB',
	'wikia-interactive-maps-image-uploads-error-empty-file' => 'An error displayed to a user when file is empty',
	'wikia-interactive-maps-image-uploads-error-bad-type' => 'An error displayed to a user when file has wrong format (not image)',
	'wikia-interactive-maps-image-uploads-error-poi-category-marker-too-small' => 'An error displayed to a user when uploaded image for custom pin type marker is too small',

	'wikia-interactive-maps-create-map-error-invalid-map-title' => 'An error message displayed above map title form field when the map title is invalid.',

	'wikia-interactive-maps-actions' => 'Text on a button near the map title that triggers dropdown with possible actions',
	'wikia-interactive-maps-delete-map' => 'Text on a button for deleting an existing map',
	'wikia-interactive-maps-delete-map-client-title' => 'Title of the "delete map" modal',
	'wikia-interactive-maps-delete-map-client-prompt' => 'Prompt (in modal) asking if user is sure to delete a map',
	'wikia-interactive-maps-delete-map-client-confirm-button' => 'Text on a button(in modal) to confirm deleting the map',
	'wikia-interactive-maps-delete-map-client-cancel-button' => 'Text on a button (in modal) to cancel deleting the map',
	'wikia-interactive-maps-delete-map-client-error' => 'Text (in modal) about error during deletion of the map',
	'wikia-interactive-maps-delete-map-success' => 'Notification text about succesful deletion of the map',

	'wikia-interactive-maps-create-poi-categories-error' => 'An error message displayed when creating pin types failed',

	'wikia-interactive-maps-log-name' => 'Name for Special:Log filter',
	'wikia-interactive-maps-log-description' => 'Description for Special:Log filter',
	'wikia-interactive-maps-create-map-log-entry' => 'Message to be displayed in the log when a map is created',
	'wikia-interactive-maps-update-map-log-entry' => 'Message to be displayed in the log when a map is updated',
	'wikia-interactive-maps-delete-map-log-entry' => 'Message to be displayed in the log when a map is deleted',
	'wikia-interactive-maps-create-pin-type-log-entry' => 'Message to be displayed in the log when a pin type is created',
	'wikia-interactive-maps-update-pin-type-log-entry' => 'Message to be displayed in the log when a pin type is updated',
	'wikia-interactive-maps-delete-pin-type-log-entry' => 'Message to be displayed in the log when a pin type is deleted',
	'wikia-interactive-maps-create-pin-log-entry' => 'Message to be displayed in the log when a pin is created',
	'wikia-interactive-maps-update-pin-log-entry' => 'Message to be displayed in the log when a pin is updated',
	'wikia-interactive-maps-delete-pin-log-entry' => 'Message to be displayed in the log when a pin is deleted',
];
