<?php

/**
 * Helper functions for Control Center
 */

class ControlCenterLogic {
	
	/**
	 * @brief Helper function which determines whether to display the Control Center Chrome in the Oasis Skin
	 * @param type $title Title of page we are on
	 * @return type boolean 
	 */
	public static function displayControlCenter($app, $title) {
		// Control center is only for logged in plus a list of groups
		// FIXME: make this a right and add it to those groups instead
		if (!$app->wg->User->isLoggedIn()) return false;
		if (!$app->wg->User->isAllowed( 'controlcenter' )) return false;
		
		$pageList = array ( "AdminDashboard", "UserRights", "ListUsers", "RecentChanges", "Categories", "MultipleUpload", "SponsorshipDashboard");
//		print_pre($title->getDBKey());
		if ($title && $title->isSpecialPage() && in_array($title->getDBKey(), $pageList)) {
			return true;
		}
		return false;
	}
	
}