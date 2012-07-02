<?php

class NavigationService {

	const version = '0.03';
	const ORIGINAL = 'original';
	const PARENT_INDEX = 'parentIndex';
	const CHILDREN = 'children';
	const DEPTH = 'depth';
	const HREF = 'href';
	const TEXT = 'text';
	const SPECIAL = 'specialAttr';
	const CANONICAL_NAME = 'canonicalName';

	const TYPE_MESSAGE = 'message';
	const TYPE_VARIABLE = 'variable';

	const COMMUNITY_WIKI_ID = 177;

	// magic word used to force given menu item to not be turned into a link (BugId:15189)
	const NOLINK = '__NOLINK__';

	const ALLOWABLE_TAGS = '';

	private $biggestCategories;
	private $lastExtraIndex = 1000;
	private $extraWordsMap = array(
		'voted' => 'GetTopVotedArticles',
		'popular' => 'GetMostPopularArticles',
		'visited' => 'GetMostVisitedArticles',
		'newlychanged' => 'GetNewlyChangedArticles',
		'topusers' => 'GetTopFiveUsers'
	);

	private $forContent = false;

	// list of errors encountered when parsing the wikitext
	private $errors = array();

	const ERR_MAGIC_WORD_IN_LEVEL_1 = 1;

	/**
	 * Return memcache key used for given message / variable
	 *
	 * City ID can be specified to return key for different wiki
	 *
	 * @param string $name message / variable name
	 * @param int $cityId city ID (false - default to current wiki)
	 * @return string memcache key
	 */
	public function getMemcKey($messageName, $cityId = false) {
		$app = F::app();

		// use cityID for production wikis
		$wikiId = (is_numeric($cityId)) ? $cityId : intval($app->wg->CityId);

		// fix for internal and staff (BugId:15149)
		if ($wikiId == 0) {
			$wikiId = $app->wg->DBname;
		}

		$messageName = str_replace(' ', '_', $messageName);

		return implode(':', array(__CLASS__, $wikiId, $messageName, self::version));
	}

	/**
	 * Return list of errors encountered when parsing the wikitext
	 *
	 * @return array list of errors
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * @author: Inez Korczyński
	 */
	public function parseMessage($messageName, Array $maxChildrenAtLevel = array(), $duration = 3600, $forContent = false, $filterInactiveSpecialPages = false ) {
		wfProfileIn( __METHOD__ );

		$nodes = $this->parseHelper(self::TYPE_MESSAGE, $messageName, $maxChildrenAtLevel, $duration, $forContent, $filterInactiveSpecialPages);

		wfProfileOut( __METHOD__ );
		return $nodes;
	}

	public function parseVariable($variableName, Array $maxChildrenAtLevel = array(), $duration = 3600, $forContent = false, $filterInactiveSpecialPages = false ) {
		wfProfileIn( __METHOD__ );

		$nodes = $this->parseHelper(self::TYPE_VARIABLE, $variableName, $maxChildrenAtLevel, $duration, $forContent, $filterInactiveSpecialPages);

		wfProfileOut( __METHOD__ );
		return $nodes;
	}

	/**
	 * Parse wikitext from given "source" - either MediaWiki message or WikiFactory variable
	 *
	 * @param string $type source type
	 * @param string $source name of message / variable to be parsed
	 * @param array $maxChildrenAtLevel allowed number of items on each menu level
	 * @param int $duration cache duration
	 * @param boolean $forContent use content language when parsing messages?
	 * @param boolean $filterInactiveSpecialPages ignore item linking to not existing special pages?
	 * @return array parsed menu wikitext
	 */
	private function parseHelper($type, $source, Array $maxChildrenAtLevel = array(), $duration = 3600, $forContent = false, $filterInactiveSpecialPages = false ) {
		wfProfileIn( __METHOD__ );
		$app = F::app();

		$this->forContent = $forContent;
		$useCache = ($app->wg->Lang->getCode() == $app->wg->ContLang->getCode()) || $this->forContent;

		if($useCache) {
			$cacheKey = $this->getMemcKey($source);
			$nodes = $app->wg->Memc->get($cacheKey);
		} else {
			$nodes = null;
		}

		if(!is_array($nodes)) {
			wfProfileIn( __METHOD__  . '::miss');

			// get wikitext from given source
			switch($type) {
				case self::TYPE_MESSAGE:
					$text = $this->forContent ? $app->wf->MsgForContent($source) : $app->wf->Msg($source);
					break;

				case self::TYPE_VARIABLE:
					// try to use "local" value
					$text = $app->getGlobal($source);

					// fallback to WikiFactory value from community (city id 177)
					if (!is_string($text)) {
						$text = WikiFactory::getVarValueByName($source, self::COMMUNITY_WIKI_ID);
					}
					break;
			}

			// and parse it
			$nodes = $this->parseText($text, $maxChildrenAtLevel, $forContent, $filterInactiveSpecialPages);

			if($useCache) {
				$app->wg->Memc->set($cacheKey, $nodes, $duration);
			}

			wfProfileOut( __METHOD__  . '::miss');
		}

		wfProfileOut( __METHOD__ );
		return $nodes;
	}

	public function parseText($text, Array $maxChildrenAtLevel = array(), $forContent = false, $filterInactiveSpecialPages = false) {
		wfProfileIn( __METHOD__ );

		$lines = explode("\n", $text);
		$this->forContent = $forContent;

		$this->errors = array();

		$nodes = $this->parseLines($lines, $maxChildrenAtLevel);
		$nodes = $this->filterSpecialPages($nodes, $filterInactiveSpecialPages);
		$nodes = $this->stripTags($nodes);

		wfProfileOut( __METHOD__ );
		return $nodes;
	}

	private function stripTags($nodes) {
		wfProfileIn( __METHOD__ );

		foreach($nodes as &$node) {
			$text = !empty($node['text']) ? $node['text'] : null;
			if( !is_null($text)) {
				$node['text'] = strip_tags($text, self::ALLOWABLE_TAGS);
			}
		}

		wfProfileOut( __METHOD__ );
		return $nodes;
	}

	private function filterSpecialPages( $nodes, $filterInactiveSpecialPages ){
		if( !$filterInactiveSpecialPages ) {
			return $nodes;
		}

		wfProfileIn( __METHOD__ );

		// filters out every special page that is not defined
		foreach( $nodes as $key => &$node ){
			if (
				isset( $node[ self::ORIGINAL ] ) &&
				stripos( $node[ self::ORIGINAL ], 'special:' ) === 0
			) {

				list(, $specialPageName) = explode( ':', $node[ self::ORIGINAL ] );
				if ( !SpecialPageFactory::exists( $specialPageName ) ){
					$inParentKey = array_search( $key, $nodes[ $node[ self::PARENT_INDEX ] ][ self::CHILDREN ]);
					// remove from parent's child list
					unset( $nodes[ $node[ self::PARENT_INDEX ] ][ self::CHILDREN ][$inParentKey] );
					// remove node
					unset( $nodes[ $key ] );
				}
				else {
					// store special page canonical name for click tracking
					$node[ self::CANONICAL_NAME ] = $specialPageName;
				}
			}
		}
		wfProfileOut( __METHOD__ );
		return $nodes;
	}

	/**
	 *
	 * @author: Inez Korczyński
	 */
	public function parseLines($lines, $maxChildrenAtLevel = array()) {
		wfProfileIn( __METHOD__ );

		$nodes = array();

		if(is_array($lines) && count($lines) > 0) {

			$lastDepth = 0;
			$i = 0;
			$lastSkip = null;

			foreach ($lines as $line ) {

				// we are interested only in lines that are not empty and start with asterisk
				if(trim($line) != '' && $line{0} == '*') {

					$depth = strrpos($line, '*') + 1;

					if($lastSkip !== null && $depth >= $lastSkip) {
						continue;
					} else {
						$lastSkip = null;
					}

					if($depth == $lastDepth + 1) {
						$parentIndex = $i;
					} else if ($depth == $lastDepth) {
						$parentIndex = $nodes[$i][ self::PARENT_INDEX ];
					} else {
						for($x = $i; $x >= 0; $x--) {
							if($x == 0) {
								$parentIndex = 0;
								break;
							}
							if($nodes[$x][ self::DEPTH ] <= $depth - 1) {
								$parentIndex = $x;
								break;
							}
						}
					}

					if(isset($maxChildrenAtLevel[$depth-1])) {
						if(isset($nodes[$parentIndex][ self::CHILDREN ])) {
							if(count($nodes[$parentIndex][ self::CHILDREN ]) >= $maxChildrenAtLevel[$depth-1]) {
								$lastSkip = $depth;
								continue;
							}
						}
					}

					$node = $this->parseOneLine($line);
					$node[ self::PARENT_INDEX ] = $parentIndex;
					$node[ self::DEPTH ] = $depth;

					$ret = $this->handleExtraWords($node, $nodes, $depth);

					if ($ret === false) {
						$this->errors[self::ERR_MAGIC_WORD_IN_LEVEL_1] = true;
					}
					else {
						$nodes[$node[ self::PARENT_INDEX ]][ self::CHILDREN ][] = $i+1;
						$nodes[$i+1] = $node;
						$lastDepth = $node[ self::DEPTH ];
						$i++;
					}
				}
			}
		}

		wfProfileOut( __METHOD__ );
		return $nodes;
	}

	/**
	 * @author: Inez Korczyński
	 */
	public function parseOneLine($line) {
		wfProfileIn( __METHOD__ );

		// trim spaces and asterisks from line and then split it to maximum two chunks
		$lineArr = explode('|', trim($line, '* '), 3);

		if ( isset( $lineArr[2] ) ){
			$specialParam = trim( addslashes( $lineArr[2] ) );
			unset( $lineArr[2] );
		} else {
			$specialParam = '';
		}

		// trim [ and ] from line to have just http://www.wikia.com instrad of [http://www.wikia.com] for external links
		$lineArr[0] = trim($lineArr[0], '[]');

		if(count($lineArr) == 2 && $lineArr[1] != '') {
			// * Foo|Bar - links with label
			$link = trim(wfMsgForContent($lineArr[0]));
			$desc = trim($lineArr[1]);
		} else {
			// * Foo
			$link = $desc = trim($lineArr[0]);

			// handle __NOLINK__ magic words (BugId:15189)
			if (substr($link, 0, 10) == self::NOLINK) {
				$link = $desc = substr($link, 10);
				$doNotLink = true;
			}
		}

		$text = $this->forContent ? wfMsgForContent( $desc ) : wfMsg( $desc );

		if(wfEmptyMsg( $desc, $text )) {
			$text = $desc;
		}

		if(wfEmptyMsg($lineArr[0], $link)) {
			$link = $lineArr[0];
		}

		if (empty($doNotLink)) {
			if(preg_match( '/^(?:' . wfUrlProtocols() . ')/', $link )) {
				$href = $link;
			} else {
				if(empty($link)) {
					$href = '#';
				} else if($link{0} == '#') {
					$href = '#';
				} else {
					$title = Title::newFromText($link);
					if(is_object($title)) {
						$href = $title->fixSpecialName()->getLocalURL();
						$pos = strpos($link, '#');
						if ($pos !== false) {
							$sectionUrl = substr($link, $pos+1);
							if ($sectionUrl !== '') {
								$href .= '#'.$sectionUrl;
							}
						}
					} else {
						$href = '#';
					}
				}
			}
		}
		else {
			$href = '#';
		}

		wfProfileOut( __METHOD__ );
		return array(
			self::ORIGINAL => $lineArr[0],
			self::TEXT => $text,
			self::HREF => $href,
			self::SPECIAL => $specialParam
		);
	}

	/**
	 * @author: Inez Korczyński
	 *
	 * Return false when given submenu should not be added in a given place
	 */
	private function handleExtraWords(&$node, &$nodes, $depth) {
		global $wgOasisNavV2;
		wfProfileIn( __METHOD__ );

		$originalLower = strtolower($node[ self::ORIGINAL ]);

		if(substr($originalLower, 0, 9) == '#category') {
			// ignore magic words in Level 1 (BugId:15240)
			if (!empty($wgOasisNavV2) && $depth == 1) {
				wfProfileOut(__METHOD__);
				return false;
			}

			$param = trim(substr($node[ self::ORIGINAL ], 9), '#');

			if(is_numeric($param)) {
				$category = $this->getBiggestCategory($param);
				$name = $category['name'];
			} else {
				$name = substr($param, 1);
			}

			$node[ self::HREF ] = Title::makeTitle(NS_CATEGORY, $name)->getLocalURL();
			if(strpos($node[ self::TEXT ], '#') === 0) {
				$node[ self::TEXT ] = str_replace('_', ' ', $name);
			}

			$data = getMenuHelper($name);

			foreach($data as $val) {
				$title = Title::newFromId($val);
				if(is_object($title)) {
					$this->addChildNode($node, $nodes, $title->getText(), $title->getLocalUrl());
				}
			}

		} else {
			$extraWord = trim($originalLower, '#');

			if(isset($this->extraWordsMap[$extraWord])) {

				if($node[ self::TEXT ]{0} == '#') {
					$node[ self::TEXT ] = wfMsg(trim($node[ self::ORIGINAL], ' *'));
				}

				$fname = $this->extraWordsMap[$extraWord];
				$data = DataProvider::$fname();
				//http://bugs.php.net/bug.php?id=46322 count(false) == 1
				if (!empty($data)) {
					// ignore magic words in Level 1 (BugId:15240)
					if (!empty($wgOasisNavV2) && $depth == 1) {
						wfProfileOut(__METHOD__);
						return false;
					}

					foreach($data as $val) {
						$this->addChildNode($node, $nodes, $val[ self::TEXT ], $val['url']);
					}
				}
			}
		}

		wfProfileOut( __METHOD__ );
		return true;
	}

	/**
	 * Add menu item as a child of given node
	 */
	private function addChildNode(&$node, &$nodes, $text, $url) {
		$node[ self::CHILDREN ][] = $this->lastExtraIndex;
		$nodes[$this->lastExtraIndex][ self::TEXT ] = $text;
		$nodes[$this->lastExtraIndex][ self::HREF ] = $url;
		$this->lastExtraIndex++;
	}

	/**
	 * @author: Inez Korczyński
	 */
	private function getBiggestCategory($index) {
		$app = F::app();

		$limit = max($index, 15);
		if($limit > count($this->biggestCategories)) {
			$key = wfMemcKey('biggest', $limit);
			$data = $app->wg->Memc->get($key);
			if(empty($data)) {
				$filterWordsA = array();
				foreach($app->wg->BiggestCategoriesBlacklist as $word) {
					$filterWordsA[] = '(cl_to not like "%'.$word.'%")';
				}
				$dbr =& wfGetDB( DB_SLAVE );
				$tables = array("categorylinks");
				$fields = array("cl_to, COUNT(*) AS cnt");
				$where = count($filterWordsA) > 0 ? array(implode(' AND ', $filterWordsA)) : array();
				$options = array("ORDER BY" => "cnt DESC", "GROUP BY" => "cl_to", "LIMIT" => $limit);
				$res = $dbr->select($tables, $fields, $where, __METHOD__, $options);
				while ($row = $dbr->fetchObject($res)) {
					$this->biggestCategories[] = array('name' => $row->cl_to, 'count' => $row->cnt);
				}
				$app->wg->Memc->set($key, $this->biggestCategories, 60 * 60 * 24 * 7);
			} else {
				$this->biggestCategories = $data;
			}
		}
		return isset($this->biggestCategories[$index-1]) ? $this->biggestCategories[$index-1] : null;
	}
}
