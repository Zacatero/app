<?php
/*
 * DataMart Service
 */

use FluentSql\StaticSQL as sql;
use Wikia\Logger\WikiaLogger;

class DataMartService {

	const PERIOD_ID_DAILY = 1;
	const PERIOD_ID_WEEKLY = 2;
	const PERIOD_ID_MONTHLY = 3;
	const PERIOD_ID_QUARTERLY = 4;
	const PERIOD_ID_YEARLY = 5;
	const PERIOD_ID_15MINS = 15;
	const PERIOD_ID_60MINS = 60;
	const PERIOD_ID_ROLLING_7DAYS = 1007; // every day
	const PERIOD_ID_ROLLING_28DAYS = 1028; // every day
	const PERIOD_ID_ROLLING_24HOURS = 10024; // every 15 minutes
	const CACHE_TOP_ARTICLES = 86400;
	const LOCK_TOP_ARTICLES = 10;

	const TOP_WIKIS_FOR_HUB = 10;
	const DEFAULT_TOP_WIKIAS_LIMIT = 200;

	const TTL = 43200; // WikiaSQL results caching time (12 hours)

	/**
	 * Get pageviews
	 *
	 * @param integer $periodId
	 * @param string $startDate [YYYY-MM-DD]
	 * @param string $endDate [YYYY-MM-DD]
	 * @param integer $wikiId
	 *
	 * @return array $pageviews [ array( 'YYYY-MM-DD' => pageviews ) ]
	 */
	protected static function getPageviews ( $periodId, $startDate, $endDate = null, $wikiId = null ) {
		$app = F::app();

		if ( empty( $wikiId ) ) {
			$wikiId = $app->wg->CityId;
		}

		if ( empty( $endDate ) ) {
			if ( $periodId == self::PERIOD_ID_MONTHLY ) {
				$endDate = date( 'Y-m-01' );
			} else {
				$endDate = date( 'Y-m-d', strtotime( '-1 day' ) );
			}
		}

		try {
			$db = DataMartService::getDB();
			$pageViews =
				( new WikiaSQL() )->skipIf( self::isDisabled() )
					->cacheGlobal( self::TTL )
					->SELECT( "date_format(time_id,'%Y-%m-%d')" )
					->AS_( 'date' )
					->FIELD( 'pageviews' )
					->AS_( 'cnt' )
					->FROM( 'rollup_wiki_pageviews' )
					->WHERE( 'period_id' )
					->EQUAL_TO( $periodId )
					->AND_( 'wiki_id' )
					->EQUAL_TO( $wikiId )
					->AND_( 'time_id' )
					->BETWEEN( $startDate, $endDate )
					->runLoop( $db, function ( &$pageViews, $row ) {
						$pageViews[$row->date] = $row->cnt;
					} );

			return $pageViews;
		} catch ( DBError $dbError ) {
			return [];
		}
	}

	/**
	 * get pageviews for list of Wikis
	 * @param integer $periodId
	 * @param array $wikis
	 * @param string $startDate [YYYY-MM-DD]
	 * @param string $endDate [YYYY-MM-DD]
	 * @return array $pageviews [ array( 'WIKI_ID' => array( 'YYYY-MM-DD' => pageviews, 'SUM' => sum(pageviews) ) ) ]
	 */
	protected static function getPageviewsForWikis ( $periodId, $wikis, $startDate, $endDate = null ) {
		if ( empty( $wikis ) ) {
			return [];
		}

		if ( empty( $endDate ) ) {
			if ( $periodId == self::PERIOD_ID_MONTHLY ) {
				$endDate = date( 'Y-m-01' );
			} else {
				$endDate = date( 'Y-m-d', strtotime( '-1 day' ) );
			}
		}

		try {
			$db = DataMartService::getDB();
			$pageviews =
				( new WikiaSQL() )->skipIf( self::isDisabled() )
					->cacheGlobal( self::TTL )
					->SELECT( 'wiki_id' )
					->FIELD( "date_format(time_id,'%Y-%m-%d')" )
					->AS_( 'date' )
					->FIELD( 'pageviews' )
					->AS_( 'cnt' )
					->FROM( 'rollup_wiki_pageviews' )
					->WHERE( 'period_id' )
					->EQUAL_TO( $periodId )
					->AND_( 'wiki_id' )
					->IN( $wikis )
					->AND_( 'time_id' )
					->BETWEEN( $startDate, $endDate )
					->runLoop( $db, function ( &$pageViews, $row ) {
						$pageViews[$row->wiki_id][$row->date] = $row->cnt;
						$pageViews[$row->wiki_id]['SUM'] += $row->cnt;
					} );

			return $pageviews;
		} catch ( DBError $dbError ) {
			return [];
		}
	}

	// get daily pageviews
	public static function getPageviewsDaily ( $startDate, $endDate = null, $wiki = null ) {
		if ( is_array( $wiki ) ) {
			$pageViews = self::getPageviewsForWikis( self::PERIOD_ID_DAILY, /* array of Wikis */
				$wiki, $startDate, $endDate );
		} else {
			$pageViews = self::getPageviews( self::PERIOD_ID_DAILY, $startDate, $endDate, /* ID */
				$wiki );
		}

		return $pageViews;
	}

	// get weekly pageviews
	public static function getPageviewsWeekly ( $startDate, $endDate = null, $wiki = null ) {
		if ( is_array( $wiki ) ) {
			$pageviews = self::getPageviewsForWikis( self::PERIOD_ID_WEEKLY, /* array of Wikis */
				$wiki, $startDate, $endDate );
		} else {
			$pageviews = self::getPageviews( self::PERIOD_ID_WEEKLY, $startDate, $endDate, /* ID */
				$wiki );
		}

		return $pageviews;
	}

	// get monthly pageviews
	public static function getPageviewsMonthly ( $startDate, $endDate = null, $wiki = null ) {
		if ( is_array( $wiki ) ) {
			$pageviews = self::getPageviewsForWikis( self::PERIOD_ID_MONTHLY, /* array of Wikis */
				$wiki, $startDate, $endDate );
		} else {
			$pageviews = self::getPageviews( self::PERIOD_ID_MONTHLY, $startDate, $endDate, /* ID */
				$wiki );
		}

		return $pageviews;
	}

	/**
	 * Get top wikis by pageviews over a specified span of time, optionally filtering by
	 * public status, language and vertical (hub)
	 *
	 * @param integer $limit The maximum number of results, defaults to 200
	 * @param array $langs (optional) The language code to use as a filter (e.g. en for English), null for all (default)
	 * @param string $hub (optional) The vertical name to use as a filter (e.g. Gaming), null for all (default)
	 * @param integer $public (optional) Filter results by public status, one of 0, 1 or null (for both, default)
	 * @return array $topWikis [ array( wikiId => pageviews ) ]
	 */
	public static function getTopWikisByPageviews ( $limit = 300, Array $langs = [], $hub = null, $public = null ) {
		$limitDefault = 300;
		$limitUsed = ( $limit < $limitDefault ) ? $limit : $limitDefault;

		$categoryId = null;
		if ( !empty( $hub ) ) {
			#external api use cases indicate that hub values passed are actually categories, not verticals,
			#so assuming that interpretation
			$categoryId = WikiFactoryHub::getInstance()->getCategoryByName( $hub )['id'];
		}

		#Latest first day of month, but no sooner than two days before to give some space for data to arrive
		$timeId = date( 'Y-m-01', strtotime('-2 day') );

		try {
			$db = DataMartService::getDB();

			$sql =
				( new WikiaSQL() )->skipIf( self::isDisabled() )
					->cacheGlobal( self::TTL )
					->SELECT( 'r.wiki_id' )
					->AS_( 'id' )
					->FIELD( 'pageviews' )
					->FROM( 'rollup_wiki_pageviews' )
					->AS_( 'r' )
					->WHERE( 'period_id' )->EQUAL_TO( DataMartService::PERIOD_ID_MONTHLY )
					->AND_( 'time_id' )->EQUAL_TO( $timeId )
					->ORDER_BY( [ 'pageviews', 'desc' ] )
					->LIMIT( $limitUsed );

			if ( is_integer( $public ) ) {
				$sql->JOIN( 'dimension_wikis' )
					->AS_( 'd' )
					->ON( 'r.wiki_id', 'd.wiki_id' )
					->AND_( 'd.public' )->EQUAL_TO( $public );
			}

			if ( !empty( $categoryId ) ) {
				$sql->JOIN( 'dimension_wiki_categories')
					->AS_('c')
					->ON( 'r.wiki_id', 'c.wiki_id' )
					->AND_( 'c.category_id' )->EQUAL_TO( $categoryId );
			}

			if ( !empty( $langs ) ) {
				$sql->AND_( 'r.lang' )->IN( $langs );
			}

			$topWikis = $sql->runLoop( $db, function ( &$topWikis, $row ) {
				$topWikis[$row->id] = $row->pageviews;
			} );

			$topWikis = array_slice( $topWikis, 0, $limit, true );

			return $topWikis;
		} catch ( DBError $dbError ) {
			return [];
		}
	}

	/**
	 * Gets user edits by user and wiki id
	 * It will be used in WAM and Interstitials
	 * @param integer|array $userIds
	 * @param integer $wikiId
	 * @return array $events [ array( 'user_id' => array() ) ]
	 * Note: number of edits includes number of creates
	 */
	public static function getUserEditsByWikiId ( $userIds, $wikiId = null ) {
		$periodId = self::PERIOD_ID_WEEKLY;
		// Every weekly rollup is made on Sundays. We need date of penultimate Sunday.
		// We dont get penultimate date of rollup from database, becasuse of performance issue
		$rollupDate = date( "Y-m-d", strtotime( "Sunday 1 week ago" ) );

		if ( empty( $userIds ) ) {
			return [];
		}

		if ( empty( $wikiId ) ) {
			global $wgCityId;

			$wikiId = $wgCityId;
		}

		// this is made because memcache key has character limit and a long
		// list of user ids can be passed so we need to have it shorter
		$userIdsKey = self::makeUserIdsMemCacheKey( $userIds );

		try {
			$events =
				WikiaDataAccess::cacheWithLock( wfSharedMemcKey( 'datamart', 'user_edits', $wikiId,
					$userIdsKey, $periodId, $rollupDate ), 86400 /* 24 hours */,
					function () use ( $wikiId, $userIds, $periodId, $rollupDate ) {
						$db = DataMartService::getDB();
						$events =
							( new WikiaSQL() )->skipIf( self::isDisabled() )
								->SELECT( 'user_id' )
								->SUM( 'creates' )
								->AS_( 'creates' )
								->SUM( 'edits' )
								->AS_( 'edits' )
								->SUM( 'deletes' )
								->AS_( 'deletes' )
								->SUM( 'undeletes' )
								->AS_( 'undeletes' )
								->FROM( 'rollup_wiki_namespace_user_events' )
								->WHERE( 'period_id' )
								->EQUAL_TO( $periodId )
								->AND_( 'wiki_id' )
								->EQUAL_TO( $wikiId )
								->AND_( 'time_id' )
								->EQUAL_TO( $rollupDate )
								->AND_( 'user_id' )
								->IN( $userIds )
								->GROUP_BY( 'user_id' )
								->runLoop( $db, function ( &$events, $row ) {
									$events[$row->user_id] = [
										'creates' => $row->creates,
										'edits' => $row->creates + $row->edits,
										'deletes' => $row->deletes,
										'undeletes' => $row->undeletes,
									];
								} );

						return $events;
					} );

			return $events;
		} catch ( DBError $dbError ) {
			return [];
		}
	}

	private static function makeUserIdsMemCacheKey( $userIds ) {
		$idsKey = md5( implode( ',', $userIds ) );
		return $idsKey;
	}

	public static function findLastRollupsDate( $period_id, $numTry = 5 ) {
		try {
			$db = DataMartService::getDB();
			// compensation for NOW
			$date = date( 'Y-m-d' ) . ' 00:00:01';
			do {
				$date =
					( new WikiaSQL() )->skipIf( self::isDisabled() )
						->SELECT( 'time_id as t' )
						->FROM( 'rollup_wiki_article_pageviews' )
						->WHERE( 'time_id' )
						->LESS_THAN( $date )
						->ORDER_BY( 'time_id' )
						->DESC()
						->LIMIT( 1 )
						->cache( self::CACHE_TOP_ARTICLES )
						->run( $db, function ( ResultWrapper $result ) {
							$row = $result->fetchObject();

							if ( $row && isset( $row->t ) ) {
								return $row->t;
							}

							return null;
						} );
				if ( !$date ) {
					break;
				}

				$found =
					( new WikiaSQL() )->skipIf( self::isDisabled() )
						->SELECT( '1 as c' )
						->FROM( 'rollup_wiki_article_pageviews' )
						->WHERE( 'time_id' )
						->EQUAL_TO( $date )
						->AND_( 'period_id' )
						->EQUAL_TO( $period_id )
						->LIMIT( 1 )
						->cache( self::CACHE_TOP_ARTICLES )
						->run( $db, function ( ResultWrapper $result ) {
							$row = $result->fetchObject();

							if ( $row && isset( $row->c ) ) {
								return $row->c;
							}

							return null;
						} );

				$numTry --;
			} while ( !$found && $numTry > 0 );

			return $date;
		} catch ( DBError $dbError ) {
			return false;
		}
	}

	/**
	 * Gets the list of top articles for a wiki on a weekly pageviews basis
	 *
	 * @param integer $wikiId A valid Wiki ID to fetch the list from
	 * @param Array $articleIds [OPTIONAL] A list of article ID's to restrict the list
	 * @param Array $namespaces [OPTIONAL] A list of namespace ID's to restrict the list (inclusive)
	 * @param boolean $excludeNamespaces [OPTIONAL] Sets $namespaces as an exclusive list, defaults to false
	 * @param integer $limit [OPTIONAL] The maximum number of items in the list, defaults to 200
	 * @param integer $rollupDate [OPTIONAL] Rollup ID to get (instead of the recent one)
	 *
	 * @return Array The list, the key contains article ID's and each item as a "namespace_id" and "pageviews" key
	 */
	private static function doGetTopArticlesByPageview(
		$wikiId,
		Array $articleIds = null,
		Array $namespaces = null,
		$excludeNamespaces = false,
		$limit = 200,
		$rollupDate = null
	) {
		$app = F::app();

		$cacheVersion = 4;
		$limitDefault = 200;
		$limitUsed = ( $limit > $limitDefault ) ? $limit : $limitDefault ;
		$keyToken = '';

		if ( !empty( $namespaces ) && is_array( $namespaces ) ) {
			$keyToken .= implode( ':', $namespaces );
		} else {
			$namespaces = null;
		}

		if ( !empty( $articleIds ) && is_array( $articleIds ) ) {
			$keyToken .= implode( ':', $articleIds );
		} else {
			$articleIds = null;
		}

		$memKey = wfSharedMemcKey(
			'datamart',
			'toparticles',
			$cacheVersion,
			$wikiId,
			$limitUsed,
			( $keyToken !== '' ) ? md5( $keyToken ) : null,
			$excludeNamespaces,
			$rollupDate ? $rollupDate : 'current'
		);

		$getData = function() use ( $app, $wikiId, $namespaces, $excludeNamespaces, $articleIds, $limitUsed, $rollupDate ) {

			/*
			the rollup_wiki_article_pageviews contains only summarized data
			with the time_id of last sunday, so fetch just that one as
			the table is partitioned on a per-day basis and crossing
			multiple partitions kills kittens
			*/

			$db = DataMartService::getDB();
			$sql = ( new WikiaSQL() )->skipIf( self::isDisabled() )
				->SELECT( 'namespace_id', 'article_id', 'pageviews as pv' )
				->FROM( 'rollup_wiki_article_pageviews' )
				->WHERE( 'time_id' )->EQUAL_TO(
						$rollupDate ? $rollupDate : sql::RAW( 'CURDATE() - INTERVAL DAYOFWEEK(CURDATE()) - 1 DAY' )
					)
					->AND_( 'period_id' )->EQUAL_TO( DataMartService::PERIOD_ID_WEEKLY )
					->AND_( 'wiki_id' )->EQUAL_TO( $wikiId )
				->ORDER_BY( ['pv', 'desc'] )
				->LIMIT( $limitUsed );

			if ( !empty( $namespaces ) ) {
				$namespaces = array_filter( $namespaces, function( $val ) {
					return is_integer( $val );
				} );

				$sql->AND_( 'namespace_id' );

				if ( !empty( $excludeNamespaces ) ) {
					$sql->NOT_IN( $namespaces );
				} else {
					$sql->IN( $namespaces );
				}
			}

			if ( !empty( $articleIds ) ) {
				$articleIds = array_filter( $articleIds, function( $val ) {
					return is_integer( $val );
				} );

				$sql->AND_( 'article_id' )->IN( $articleIds );
			}

			$topArticles = $sql->runLoop( $db, function( &$topArticles, $row ) {
				$topArticles[$row->article_id] = [
					'namespace_id' => $row->namespace_id,
					'pageviews' => $row->pv
				];
			} );

			return $topArticles;
		};

		$topArticles = WikiaDataAccess::cacheWithLock(
			$memKey,
			self::CACHE_TOP_ARTICLES,
			$getData,
			WikiaDataAccess::USE_CACHE,
			self::LOCK_TOP_ARTICLES
		);

		$topArticles = array_slice( $topArticles, 0, $limit, true );

		return $topArticles;
	}

	/**
	 * Gets the list of top articles for a wiki on a weekly pageviews basis
	 *
	 * It internally calls doGetTopArticlesByPageview() method,
	 * but applies a fallback to the last rollup when the current one is not replicated
	 *
	 * It's A Nasty And Ugly Hack (TM) before we have a proper rollups solution.
	 *
	 * @see https://wikia-inc.atlassian.net/browse/OPS-5465
	 *
	 * @param integer $wikiId A valid Wiki ID to fetch the list from
	 * @param Array $articleIds [OPTIONAL] A list of article ID's to restrict the list
	 * @param Array $namespaces [OPTIONAL] A list of namespace ID's to restrict the list (inclusive)
	 * @param boolean $excludeNamespaces [OPTIONAL] Sets $namespaces as an exclusive list, defaults to false
	 * @param integer $limit [OPTIONAL] The maximum number of items in the list, defaults to 200
	 * @param integer $rollupDate [OPTIONAL] Rollup ID to get (instead of the recent one)
	 *
	 * @return array The list, the key contains article ID's and each item as a "namespace_id"
	 * and "pageviews" key
	 */
	public static function getTopArticlesByPageview(
		$wikiId,
 		Array $articleIds = null,
		Array $namespaces = null,
		$excludeNamespaces = false,
		$limit = 200,
		$rollupDate = null
	) {
		try {
			$articles =
				self::doGetTopArticlesByPageview( $wikiId, $articleIds, $namespaces,
					$excludeNamespaces, $limit, $rollupDate );

			if ( empty( $articles ) ) {
				// log when the fallback takes place
				WikiaLogger::instance()->error( __METHOD__ . ' fallback', [
					'wiki_id' => $wikiId,
					'rollup_date' => $rollupDate
				] );

				$fallbackDate = self::findLastRollupsDate( self::PERIOD_ID_WEEKLY );
				if ( $fallbackDate ) {
					$articles =
						self::doGetTopArticlesByPageview( $wikiId, $articleIds, $namespaces,
							$excludeNamespaces, $limit, $fallbackDate );
				}
			}

			return $articles;
		} catch ( DBError $dbError ) {
			return [];
		}
	}

	/**
	 * Get most popular cross wiki articles based on pageviews in last week.
	 * Unfortunately according to performance reasons we need to fetch most popular wikis
	 * and then fetch most popular articles on those wikis.
	 *
	 * @param string $hub
	 * @param string $langs
	 * @param array|null $namespaces
	 * @param int $limit
	 * @return array of wikis with articles. Format:
	 * [
	 *   [
	 *     'wiki' => [
	 *       'id' => 1030786,
	 *       'name' => 'Wiki name',
	 *       'language' => 'language code',
	 *       'domain' => 'Full url',
	 *     ],
	 *     'articles' => [
	 *        ['id' => 1, 'ns' => 0],
	 *        ['id' => 2, 'ns' => -1]
	 *      ]
	 *   ],
	 *   [
	 *     'wiki' => [
	 *       'id' => 1030786,
	 *       'name' => 'Wiki name',
	 *       'language' => 'language code',
	 *       'domain' => 'Full url',
	 *     ],
	 *     'articles' => [
	 *        ['id' => 1, 'ns' => 0],
	 *        ['id' => 2, 'ns' => -1]
	 *      ]
	 *   ],
	 * ]
	 */
	public static function getTopCrossWikiArticlesByPageview( $hub, $langs, $namespaces = null, $limit = 200 ) {
		// fetch the top 10 wikis on a weekly pageviews basis
		// this has it's own cache
		$wikis = DataMartService::getTopWikisByPageviews(
			self::TOP_WIKIS_FOR_HUB,
			$langs,
			$hub,
			1 /* only pubic */
		);

		$wikisCount = count( $wikis );
		$res = [];

		if ( $wikisCount >= 1 ) {
			$articlesPerWiki = ceil( $limit / $wikisCount );

			// fetch $articlesPerWiki articles from each wiki
			// see FB#73094 for performance review
			foreach ( $wikis as $wikiId => $data ) {
				// this has it's own cache
				$articles = DataMartService::getTopArticlesByPageview(
					$wikiId,
					null,
					$namespaces,
					false,
					$articlesPerWiki
				);

				if ( count( $articles ) == 0 ) {
					continue;
				}

				$item = [
					'wiki' => [
						'id' => $wikiId,
						// WF data has it's own cache
						'name' => WikiFactory::getVarValueByName( 'wgSitename', $wikiId ),
						'language' => WikiFactory::getVarValueByName( 'wgLanguageCode', $wikiId ),
						// // language-path - can clients handle the language path after the domain name?
						'domain' => WikiFactory::cityIDtoUrl( $wikiId )
					],
					'articles' => []
				];

				foreach ( $articles as $articleId => $article ) {
					$item['articles'][] = [
						'id' => $articleId,
						'ns' => $article['namespace_id']
					];
				}

				$res[] = $item;
			}
		}

		return $res;
	}

	/**
	 * Gets page views for given articles
	 *
	 * @param array $articlesIds
	 * @param datetime $timeId
	 * @param int $wikiId
	 * @param int $periodId
	 * @return array
	 */
	public static function getPageViewsForArticles( Array $articlesIds, $timeId, $wikiId, $periodId = self::PERIOD_ID_WEEKLY ) {
		try {
			$db = DataMartService::getDB();

			$articlePageViews =
				( new WikiaSQL() )->skipIf( self::isDisabled() )
					->SELECT( 'article_id', 'pageviews' )
					->FROM( 'rollup_wiki_article_pageviews' )
					->WHERE( 'article_id' )
					->IN( $articlesIds )
					->AND_( 'time_id' )
					->EQUAL_TO( $timeId )
					->AND_( 'wiki_id' )
					->EQUAL_TO( intval( $wikiId ) )
					->AND_( 'period_id' )
					->EQUAL_TO( intval( $periodId ) )
					->runLoop( $db, function ( &$articlePageViews, $row ) {
						$articlePageViews[$row->article_id] = $row->pageviews;
					} );

			return $articlePageViews;
		} catch ( DBError $dbError ) {
			return [];
		}
	}

	protected static function getDB() {
		$app = F::app();
		$db = wfGetDB( DB_SLAVE, array(), $app->wg->DWStatsDB );
		$db->clearFlag( DBO_TRX );
		return $db;
	}

	/**
	 * wgStatsDBEnabled can be used to disable queries to statsdb_mart database
	 *
	 * @return bool
	 */
	protected static function isDisabled() {
		return empty( F::app()->wg->StatsDBEnabled );
	}

}
