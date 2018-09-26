<?php
$wgExtensionCredits['other'][] = [
	'name' => 'Auth Preferences',
	'author' => 'Wikia, Inc.',
	'descriptionmsg' => 'authconnect-desc',
	'url' => 'https://github.com/Wikia/app/tree/dev/extensions/wikia/AuthPreferences',
];

$wgExtensionMessagesFiles['AuthPreferences'] = __DIR__ . '/AuthPreferences.i18n.php';

$wgAutoloadClasses['AuthPreferencesController'] = __DIR__ . '/AuthPreferencesController.php';
$wgAutoloadClasses['AuthPreferencesModuleService'] = __DIR__ . '/AuthPreferencesModuleService.php';
$wgAutoloadClasses['AuthPreferencesHooks'] = __DIR__ . '/AuthPreferencesHooks.php';

$wgHooks['GetPreferences'][] = 'AuthPreferencesHooks::onGetPreferences';

$wgResourceModules['ext.wikia.authPreferences'] = [
	'scripts' => [
		'modules/ext.wikia.authPreferences.js',
	],
	'styles' => [
		'modules/ext.wikia.authPreferences.css',
	],
	'messages' => [
		'fbconnect-preferences-connected',
		'fbconnect-preferences-connected-error',
		'fbconnect-disconnect-info',
		'fbconnect-unknown-error',
		'fbconnect-error-fb-account-in-use',
		'google-convert',
	],
	
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'wikia/AuthPreferences',
];
