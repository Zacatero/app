<?php

namespace Email\Controller;

use Email;
use Email\Check;
use Email\EmailController;
use Email\Tracking\TrackingCategories;

abstract class FounderController extends EmailController {
	// Defaults; will be overridden in subclasses
	const TRACKING_CATEGORY_EN = TrackingCategories::DEFAULT_CATEGORY;
	const TRACKING_CATEGORY_INT = TrackingCategories::DEFAULT_CATEGORY;

	/**
	 * Determine which sendgrid category to send based on target language and specific
	 * founder email being sent. See dependent classes for overridden values
	 *
	 * @return string
	 */
	public function getSendGridCategory() {
		return strtolower( $this->targetLang ) == 'en'
			? static::TRACKING_CATEGORY_EN
			: static::TRACKING_CATEGORY_INT;
	}

}

abstract class AbstractFounderEditController extends FounderController {

	/** @var \Title */
	protected $pageTitle;

	protected $previousRevId;
	protected $currentRevId;

	public function initEmail() {
		// This title is for the article being commented upon
		$titleText = $this->request->getVal( 'pageTitle' );
		$titleNamespace = $this->request->getVal( 'pageNs' );

		$this->pageTitle = \Title::newFromText( $titleText, $titleNamespace );

		$this->previousRevId = $this->request->getVal( 'previousRevId' );
		$this->currentRevId = $this->request->getVal( 'currentRevId' );

		$this->assertValidParams();
	}

	/**
	 * Validate the params passed in by the client
	 */
	private function assertValidParams() {
		$this->assertValidTitle();
		$this->assertValidRevisionIds();
	}

	/**
	 * @throws \Email\Check
	 */
	private function assertValidTitle() {
		if ( !$this->pageTitle instanceof \Title ) {
			throw new Check( "Invalid value passed for title" );
		}

		if ( !$this->pageTitle->exists() ) {
			throw new Check( "Title doesn't exist." );
		}
	}

	/**
	 * @throws \Email\Check
	 */
	private function assertValidRevisionIds() {
		if ( empty( $this->previousRevId ) ) {
			throw new Check( "Invalid value passed for previousRevId" );
		}

		if ( empty( $this->currentRevId ) ) {
			throw new Check( "Invalid value passed for currentRevId" );
		}
	}

	/**
	 * @template avatarLayout
	 */
	public function body() {
		$this->response->setData( [
			'salutation' => $this->getSalutation(),
			'editorProfilePage' => $this->getCurrentProfilePage(),
			'editorUserName' => $this->getCurrentUserName(),
			'editorAvatarURL' => $this->getCurrentAvatarURL(),
			'summary' => $this->getSummary(),
			'buttonText' => $this->getChangesLabel(),
			'buttonLink' => $this->getChangesLink(),
			'contentFooterMessages' => [
				$this->getFooterEncouragement(),
				$this->getFooterArticleLink(),
				$this->getFooterAllChangesLink(),
			],
			'details' => $this->getDetails(),
			'hasContentFooterMessages' => true
		] );
	}

	public function getSubject() {
		$articleTitle = $this->pageTitle->getText();
		$name = $this->getCurrentUserName();

		return $this->getMessage( 'emailext-founder-subject', $articleTitle, $name )
			->text();
	}

	protected function getSummary() {
		$articleUrl = $this->pageTitle->getFullURL();
		$articleTitle = $this->pageTitle->getText();

		return $this->getMessage( 'emailext-founder-summary', $articleUrl, $articleTitle )
			->parse();
	}

	protected function getDetails() {
		$article = \Article::newFromTitle( $this->pageTitle, \RequestContext::getMain() );
		$service = new \ArticleService( $article );
		$snippet = $service->getTextSnippet();

		return $snippet;
	}

	protected function getChangesLabel() {
		return $this->getMessage( 'emailext-founder-link-label' )->parse();
	}

	protected function getChangesLink() {
		return $this->pageTitle->getFullURL( [
			'diff' => $this->currentRevId,
			'oldid' => $this->previousRevId,
		] );
	}

	protected function getFooterEncouragement() {
		$name = $this->getCurrentUserName();
		$profileUrl = $this->getCurrentProfilePage();

		return $this->getMessage( $this->getFooterEncouragementKey(), $profileUrl, $name )
			->parse();
	}

	protected function getFooterEncouragementKey() {
		return 'emailext-founder-encourage';
	}

	protected function getFooterArticleLink() {
		$articleTitle = $this->pageTitle->getText();
		$url = $this->pageTitle->getFullURL( [
			'diff' => $this->currentRevId,
		] );

		return $this->getMessage( 'emailext-founder-footer-article', $url, $articleTitle )
			->parse();
	}

	protected function getFooterAllChangesLink() {
		$articleTitle = $this->pageTitle->getText();
		$url = $this->pageTitle->getFullURL( [
			'action' => 'history'
		] );

		return $this->getMessage( 'emailext-founder-footer-all-changes', $url, $articleTitle )
			->parse();
	}

	/**
	 * Form fields required for this email for Special:SendEmail. See
	 * EmailController::getEmailSpecificFormFields for more info.
	 * @return array
	 */
	protected static function getEmailSpecificFormFields() {
		$formFields = [
			'inputs' => [
				[
					'type' => 'text',
					'name' => 'pageTitle',
					'label' => "Article Title",
					'tooltip' => "eg 'Rachel_Berry' (make sure it's on this wikia!)"
				],
				[
					'type' => 'hidden',
					'name' => 'pageNs',
					'value' => NS_MAIN
				],
				[
					'type' => 'text',
					'name' => 'previousRevId',
					'label' => "Previous revision ID",
					'tooltip' => "Use the 'oldid' parameter from an article diff"
				],
				[
					'type' => 'text',
					'name' => 'currentRevId',
					'label' => "Current revision ID",
					'tooltip' => "Use the 'diff' parameter from an article diff"
				],
			]
		];

		return array_merge_recursive( $formFields, parent::getEmailSpecificFormFields() );
	}

}

class FounderEditController extends AbstractFounderEditController {
	const TRACKING_CATEGORY_EN = TrackingCategories::FOUNDER_FIRST_EDIT_USER_EN;
	const TRACKING_CATEGORY_INT = TrackingCategories::FOUNDER_FIRST_EDIT_USER_INT;
}

class FounderMultiEditController extends AbstractFounderEditController {
	const TRACKING_CATEGORY_EN = TrackingCategories::FOUNDER_EDIT_USER_EN;
	const TRACKING_CATEGORY_INT = TrackingCategories::FOUNDER_EDIT_USER_INT;

	protected function getFooterEncouragementKey() {
		return 'emailext-founder-multi-encourage';
	}
}

class FounderAnonEditController extends AbstractFounderEditController {
	const TRACKING_CATEGORY_EN = TrackingCategories::FOUNDER_EDIT_ANON_EN;
	const TRACKING_CATEGORY_INT = TrackingCategories::FOUNDER_EDIT_ANON_INT;

	public function getSubject() {
		$articleTitle = $this->pageTitle->getText();

		return $this->getMessage( 'emailext-founder-anon-subject', $articleTitle )
			->text();
	}

	protected function getFooterEncouragement() {
		return $this->getMessage( 'emailext-founder-anon-encourage' )
			->parse();
	}


	/**
	 * Form fields required for this email for Special:SendEmail. See
	 * EmailController::getEmailSpecificFormFields for more info.
	 * @return array
	 */
	protected static function getEmailSpecificFormFields() {
		$formFields = [
			'inputs' => [
				[
					'type' => 'hidden',
					'name' => 'currentUser',
					'value' => -1
				],
			]
		];

		return array_merge_recursive( $formFields, parent::getEmailSpecificFormFields() );
	}
}

class FounderNewMemberController extends FounderController {
	const TRACKING_CATEGORY_EN = TrackingCategories::FOUNDER_NEW_MEMBER_EN;
	const TRACKING_CATEGORY_INT = TrackingCategories::FOUNDER_NEW_MEMBER_INT;

	/**
	 * @template avatarLayout
	 */
	public function body() {
		$this->response->setData( [
			'salutation' => $this->getSalutation(),
			'editorProfilePage' => $this->getCurrentProfilePage(),
			'editorUserName' => $this->getCurrentUserName(),
			'editorAvatarURL' => $this->getCurrentAvatarURL(),
			'summary' => $this->getSummary(),
			'buttonText' => $this->getButtonText(),
			'buttonLink' => $this->getButtonLink(),
			'details' => $this->getDetails(),
		] );
	}

	public function getSubject() {
		return $this->getMessage( 'emailext-founder-new-member-subject', $this->currentUser->getName() )->parse();
	}

	// Same message use for subject and summary
	public function getSummary() {
		return $this->getSubject();
	}

	public function getDetails() {
		return $this->getMessage( 'emailext-founder-new-member-details', $this->currentUser->getName() )->parse();
	}

	public function getButtonText() {
		return $this->getMessage( 'emailext-founder-new-member-link-label' )->text();
	}

	public function getButtonLink() {
		return $this->currentUser->getTalkPage()->getFullURL();
	}

	public function assertCanEmail() {
		parent::assertCanEmail();
		$this->assertFounderSubscribedToDigest();
		$this->assertFounderWantsNewMembersEmail();
	}

	/**
	 * If the founder is subscribed to the founder's digest, don't send them an individual email informing them
	 * a new user joined their wiki. They'll learn about that in the digest.
	 * @throws \Email\Check
	 */
	public function assertFounderSubscribedToDigest() {
		$wikiId = \F::app()->wg->CityId;
		if ( $this->targetUser->getBoolOption( "founderemails-complete-digest-$wikiId" ) ) {
			throw new Check( 'Digest mode is enabled, do not create user registration event notifications' );
		}
	}

	/**
	 * @throws \Email\Check
	 */
	public function assertFounderWantsNewMembersEmail() {
		$wikiId = \F::app()->wg->CityId;
		if ( !$this->targetUser->getBoolOption( "founderemails-joins-$wikiId"  ) ) {
			throw new Check( "Founder doesn't want to be emailed about new members joining this wiki" );
		}
	}
}

class FounderTipsController extends FounderController {
	const TRACKING_CATEGORY_EN = TrackingCategories::FOUNDER_ACTIVITY_DIGEST_EN;
	const TRACKING_CATEGORY_INT = TrackingCategories::FOUNDER_ACTIVITY_DIGEST_INT;

	const LAYOUT_CSS = "founderTips.css";

	protected static $ICONS = [
		[
			"iconSrc" => "Add_page",
			"iconLink" => "CreatePage",
			"IconLinkParams" => [ "modal" => "AddPage" ],
			"detailsHeaderKey" => "emailext-founder-add-pages-header",
			"detailsKey" => "emailext-founder-add-pages-details"
		],
		[
			"iconSrc" => "Add_photo",
			"iconLink" => "NewFiles",
			"IconLinkParams" => [ "modal" => "UploadImage" ],
			"detailsHeaderKey" => "emailext-founder-add-photos-header",
			"detailsKey" => "emailext-founder-add-photos-details"
		],
		[
			"iconSrc" => "Customize",
			"iconLink" => "Main", // TODO Localize this bitch
			"IconLinkParams" => [ "action" => "edit" ],
			"detailsHeaderKey" => "emailext-founder-customize-header",
			"detailsKey" => "emailext-founder-customize-details"
		],
		[
			"iconSrc" => "Get-exposure",
			"detailsHeaderKey" => "emailext-founder-exposure-header",
			"detailsKey" => "emailext-founder-exposure-details"
		],
		[
			"iconSrc" => "Share",
			"detailsHeaderKey" => "emailext-founder-share-header",
			"detailsKey" => "emailext-founder-share-details"
		],
	];


	protected $wikiName;
	protected $wikiId;

	public function initEmail() {
		$this->wikiName = $this->getVal( 'wikiName', 'SOME SUPER COOL WIKIA' );
		$this->wikiId = $this->getVal( 'wikiId', 869155 );
	}

	protected function getSubject() {
		return $this->getMessage( 'emailext-founder-newly-created-subject', $this->wikiName )->parse();
	}

	/**
	 * @template founderTips
	 */
	public function body() {
		$this->response->setData( [
			'salutation' => $this->getSalutation(),
			'summary' => $this->getMessage( 'emailext-founder-newly-created-summary', $this->wikiName )->parse(),
			'extendedSummary' => $this->getMessage( 'emailext-founder-newly-created-summary-extended' )->text(),
			'details' => $this->getDetailsList(),
			'contentFooterMessages' => [
				$this->getMessage( 'emailext-founder-visit-community', $this->wikiName )->parse(),
				$this->getMessage( 'emailext-founder-happy-wikia-building' )->text(),
				$this->getMessage( 'emailext-emailconfirmation-community-team' )->text(),
			],
		] );
	}

	/**
	 * Returns list of details for the digest
	 *
	 * @return array
	 */
	protected function getDetailsList() {
		$detailsList = [];
		foreach ( self::$ICONS as $icon ) {
			$detailsList[] = [
				"detailsHeader" => $this->getMessage( $icon["detailsHeaderKey"] )->text(),
				"details" => $this->getMessage( $icon["detailsKey"] )->text(),
				"iconSrc" => Email\ImageHelper::getFileInfo( $icon['iconSrc'], ".png" )['url'],
				"iconLink" => empty( $icon["iconLink"] ) ? "" :
						\GlobalTitle::newFromText( $icon["iconLink"], NS_SPECIAL, $this->wikiId )->getFullURL( $icon["IconLinkParams"] )
			];
		}

		return $detailsList;
	}


}

class FounderTipsThreeDaysController extends FounderTipsController {
	const TRACKING_CATEGORY_EN = TrackingCategories::FOUNDER_ACTIVITY_DIGEST_EN;
	const TRACKING_CATEGORY_INT = TrackingCategories::FOUNDER_ACTIVITY_DIGEST_INT;

	protected static $ICONS = [
		[
			"iconSrc" => "Add_photo",
			"iconLink" => "Videos",
			"IconLinkParams" => [],
			"detailsHeaderKey" => "emailext-founder-add-photos-header",
			"detailsKey" => "emailext-founder-add-photos-details"
		],
		[
			"iconSrc" => "Update-theme",
			"iconLink" => "ThemeDesigner",
			"IconLinkParams" => [],
			"detailsHeaderKey" => "emailext-founder-customize-header",
			"detailsKey" => "emailext-founder-customize-details"
		],
		[
			"iconSrc" => "Get-exposure", /// TODO Figure out how to add the WAM link here
			"detailsHeaderKey" => "emailext-founder-exposure-header",
			"detailsKey" => "emailext-founder-exposure-details"
		],
	];


	protected $wikiName;
	protected $wikiId;

	protected function getSubject() {
		return $this->getMessage( 'emailext-founder-3-days-subject', $this->wikiName )->parse();
	}

	/**
	 * @template founderTips
	 */
	public function body() {
		$this->response->setData( [
			'salutation' => $this->getSalutation(),
			'summary' => $this->getMessage( 'emailext-founder-3-days-summary', $this->wikiName )->parse(),
			'extendedSummary' => $this->getMessage( 'emailext-founder-3-days-extended-summary' )->text(),
			'details' => $this->getDetailsList(),
			'contentFooterMessages' => [
				$this->getMessage( 'emailext-founder-3-days-need-help', $this->wikiName )->parse(),
				$this->getMessage( 'emailext-founder-3-days-great-work' )->text(),
				$this->getMessage( 'emailext-emailconfirmation-community-team' )->text(),
			],
		] );
	}

	/**
	 * Returns list of details for the digest
	 *
	 * @return array
	 */
	protected function getDetailsList() {
		$detailsList = [];
		foreach ( self::$ICONS as $icon ) {
			$detailsList[] = [
				"detailsHeader" => $this->getMessage( $icon["detailsHeaderKey"] )->text(),
				"details" => $this->getMessage( $icon["detailsKey"] )->text(),
				"iconSrc" => Email\ImageHelper::getFileInfo( $icon['iconSrc'], ".png" )['url'],
				"iconLink" => empty( $icon["iconLink"] ) ? "" :
						\GlobalTitle::newFromText( $icon["iconLink"], NS_SPECIAL, $this->wikiId )->getFullURL( $icon["IconLinkParams"] )
			];
		}

		return $detailsList;
	}


}
class FounderTipsTenDaysController extends FounderTipsController {
	const TRACKING_CATEGORY_EN = TrackingCategories::FOUNDER_ACTIVITY_DIGEST_EN;
	const TRACKING_CATEGORY_INT = TrackingCategories::FOUNDER_ACTIVITY_DIGEST_INT;

	protected static $ICONS = [
		[
			"iconSrc" => "Share",
			"detailsHeaderKey" => "emailext-founder-10-days-sharing-header",
			"detailsKey" => "emailext-founder-10-days-sharing-details"
		],
		[
			"iconSrc" => "Power-of-email",
			"detailsHeaderKey" => "emailext-founder-10-days-email-power-header",
			"detailsKey" => "emailext-founder-10-days-email-power-details"
		],
		[
			"iconSrc" => "Get-with-google", /// TODO Figure out how to add the Google link here
			"detailsHeaderKey" => "emailext-founder-10-days-email-google-header",
			"detailsKey" => "emailext-founder-10-days-email-google-details"
		],
	];


	protected $wikiName;
	protected $wikiId;

	protected function getSubject() {
		return $this->getMessage( 'emailext-founder-3-days-subject', $this->wikiName )->parse();
	}

	/**
	 * @template founderTips
	 */
	public function body() {
		$this->response->setData( [
			'salutation' => $this->getSalutation(),
			'summary' => $this->getMessage( 'emailext-founder-3-days-summary', $this->wikiName )->parse(),
			'extendedSummary' => $this->getMessage( 'emailext-founder-3-days-extended-summary' )->text(),
			'details' => $this->getDetailsList(),
			'contentFooterMessages' => [
				$this->getMessage( 'emailext-founder-10-days-email-what-next' )->text(),
				$this->getMessage( 'emailext-emailconfirmation-community-team' )->text(),
			],
		] );
	}

	/**
	 * Returns list of details for the digest
	 *
	 * @return array
	 */
	protected function getDetailsList() {
		$detailsList = [];
		foreach ( self::$ICONS as $icon ) {
			$detailsList[] = [
				"detailsHeader" => $this->getMessage( $icon["detailsHeaderKey"] )->text(),
				"details" => $this->getMessage( $icon["detailsKey"] )->text(),
				"iconSrc" => Email\ImageHelper::getFileInfo( $icon['iconSrc'], ".png" )['url'],
				"iconLink" => empty( $icon["iconLink"] ) ? "" :
						\GlobalTitle::newFromText( $icon["iconLink"], NS_SPECIAL, $this->wikiId )->getFullURL( $icon["IconLinkParams"] )
			];
		}

		return $detailsList;
	}
}
