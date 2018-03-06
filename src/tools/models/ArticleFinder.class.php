<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018 DraiWiki
 * @license     Apache 2.0
 */

namespace DraiWiki\src\tools\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\core\models\InputValidator;
use DraiWiki\src\core\models\Sanitizer;
use DraiWiki\src\main\models\ModelHeader;
use Aidantwoods\SecureParsedown\SecureParsedown;

/**
 * Class ArticleFinder
 * Implementation of search functionality.
 * @package DraiWiki\src\tools\models
 * @since 1.0 Alpha 1
 */
class ArticleFinder extends ModelHeader {

    /**
     * @var string $_unparsedSearchTerms The value of $_POST['search_terms']
     */
    private $_unparsedSearchTerms;

    /**
     * @var array $_terms Regular (optional) search terms. At least one of them must be found
     */
    private $_terms = [];

    /**
     * @var array $_requiredTerms Required search terms. Each article must contain these.
     */
    private $_requiredTerms = [];

    /**
     * @var array $_excludedTerms Excluded search them. Articles that contain one of these keywords are not displayed.
     */
    private $_excludedTerms = [];

    /**
     * @var array $_articles An array of articles that matches the search requirements
     */
    private $_articles = [];

    /**
     * @var int $_start When to start adding found articles to the articles array
     */
    private $_start;

    /**
     * @var int $_maxResults How many articles should be displayed per page?
     */
    private $_maxResults;

    /**
     * @var int $_maxPoints The highest number of points we've encountered
     */
    private $_maxPoints;

    /**
     * @var Parsedown $_parsedown An object of the Parsedown class, used for parsing Markdown
     */
    private $_parsedown;

    /**
     * @var bool $_errorOccurred If this is set to true, we can't load search results
     */
    private $_errorOccurred;

    /**
     * @var bool $_hasLoadedArticles Whether or not an attempt was made to load articles (if set to false, nothing has been submitted or an error occurred)
     */
    private $_hasLoadedArticles;

    /**
     * @var int $_resultCount The total number of results. This includes all results, not just the ones currently being displayed
     */
    private $_resultCount;

    /**
     * @var string $_request The current AJAX request (if there is any)
     */
    private $_request;

    /**
     * @var bool $_ignoreLocales If set to false, this will only load articles written in the interface language
     */
    private $_ignoreLocales;

    /**
     * Creates a new object of the ArticleFinder class.
     * @param string $unparsedSearchTerms The value of $_POST['search_terms']
     * @param int $start When to start adding found articles to the articles array
     * @param int $maxResults How many articles should be displayed per page?
     * @param bool $ignoreLocales If set to true, the article finder will load results from ALL locales
     */
    public function __construct(?string $unparsedSearchTerms = '', int $start = 0, int $maxResults = 15, bool $ignoreLocales = false) {
        $this->_unparsedSearchTerms = trim($unparsedSearchTerms);
        $this->loadLocale();
        $this->loadUser();

        $this->_start = $start;
        $this->_maxResults = $maxResults;
        $this->_hasLoadedArticles = false;
        $this->_ignoreLocales = $ignoreLocales;

        $this->_parsedown = new SecureParsedown();
        $this->_parsedown->setSafeMode(true);
    }

    /**
     * Parse the search terms and add them to the appropriate array
     * @param array $errors An array, passed by reference, that is used for storing errors (if there are any)
     * @return void
     */
    public function parse(array &$errors) : void {
        $lastCharacter = '';

        $currentTermType = 'regular';

        $currentTerm = '';
        $currentExcludedTerm = '';
        $currentRequiredTerm = '';

        $endOfSearchTerms = false;

        for ($i = 0; $i <= ($length = strlen($term = $this->_unparsedSearchTerms)); $i++) {
            if ($i == $length)
                $endOfSearchTerms = true;

            if (!$endOfSearchTerms && preg_match('/\p{P}/', $term[$i]) && $term[$i] != '"' && $term[$i] != '-')
                continue;

            if (($endOfSearchTerms || $term[$i] == ' ' || ($lastCharacter == '"' && $currentTermType == 'required')) && (!empty($currentTerm) || !empty($currentRequiredTerm) || !empty($currentExcludedTerm))) {
                switch ($currentTermType) {
                    case 'required':
                        if ($lastCharacter == '"' && ($endOfSearchTerms || (!$endOfSearchTerms && $term[$i] == ' '))) {
                            $currentTermType = 'regular';

                            if (!empty($currentRequiredTerm)) {
                                $this->_requiredTerms[] = $currentRequiredTerm;
                                $currentRequiredTerm = '';
                            }
                        }

                        else if ($term[$i] == ' ')
                            $currentRequiredTerm .= ' ';

                        break;
                    case 'exclude':
                        if (!empty($currentExcludedTerm))
                            $this->_excludedTerms[] = $currentExcludedTerm;

                        $currentTermType = 'regular';
                        $currentExcludedTerm = '';
                        break;
                    default:
                        if (!empty($currentTerm))
                            $this->_terms[] = $currentTerm;

                        $currentTerm = '';
                }

                if (!$endOfSearchTerms)
                    $lastCharacter = $term[$i];

                continue;
            }

            if ($currentTermType == 'regular' && $lastCharacter == '"' && $term[$i] != ' ') {
                $errors[] = _localized('find.invalid_format_required_regular_divide');
                $this->_errorOccurred = true;
                return;
            }

            if ($term[$i] == '-') {
                $currentTermType = 'exclude';
                $lastCharacter = '-';
                continue;
            }
            else if ($term[$i] == '"') {
                $currentTermType = 'required';
                $lastCharacter = '"';
                continue;
            }

            switch ($currentTermType) {
                case 'required':
                    $currentRequiredTerm .= $term[$i];
                    break;
                case 'exclude':
                    $currentExcludedTerm .= $term[$i];
                    break;
                default:
                    $currentTerm .= $term[$i];
            }

            $lastCharacter = $term[$i];
        }

        foreach (array_merge($this->_requiredTerms, $this->_terms, $this->_excludedTerms) as $term) {
            if (strlen($term) < ($min = self::$config->read('min_search_term_length'))) {
                $errors[] = sprintf(_localized('find.search_term_too_short'), $min);
                break;
            }
        }
    }

    /**
     * Use the parsed search terms to find matching articles
     * @return void
     */
    public function loadResults() : void {
        $conditions = 'WHERE ';

        $params = [];
        $paramCount = 0;

        for ($i = 0; $i < count($this->_excludedTerms); $i++) {
            if ($i != 0)
                $conditions .= ' AND';

            $conditions .= ' a.title NOT LIKE CONCAT (\'%\', :param' . $paramCount  . ', \'%\') AND h.body NOT LIKE CONCAT (\'%\', :param' . ($paramCount + 1)  . ', \'%\')';

            $params['param' . $paramCount++] = $this->_excludedTerms[$i];
            $params['param' . $paramCount++] = $this->_excludedTerms[$i];
        }

        for ($i = 0; $i < count($this->_requiredTerms); $i++) {
            if ($paramCount != 0 || $i > 0)
                $conditions .= ' AND';

            $conditions .= ' (a.title LIKE CONCAT (\'%\', :param' . $paramCount  . ', \'%\') OR h.body LIKE CONCAT (\'%\', :param' . $paramCount  . ', \'%\'))';

            $params['param' . $paramCount++] = $this->_requiredTerms[$i];
            $params['param' . $paramCount++] = $this->_requiredTerms[$i];
        }

        if ($paramCount != 0 && count($this->_terms) > 0)
            $conditions .= ' AND (';
        else if (count($this->_terms) > 0)
            $conditions .= ' (';

        for ($i = 0; $i < count($this->_terms); $i++) {
            if ($i != 0)
                $conditions .= ' OR';

            $conditions .= ' a.title LIKE CONCAT (\'%\', :param' . $paramCount  . ', \'%\') OR h.body LIKE CONCAT (\'%\', :param' . $paramCount  . ', \'%\')';

            $params['param' . $paramCount++] = $this->_terms[$i];
            $params['param' . $paramCount++] = $this->_terms[$i];
        }

        if (count($this->_terms) > 0)
            $conditions .= ')';

        $query = QueryFactory::produce('select', '
            SELECT a.id, a.title, h.body, h.user_id
                FROM {db_prefix}article_history h
                INNER JOIN {db_prefix}article a ON (a.id = h.article_id)
                ' . $conditions . '
                ' . ($this->_ignoreLocales ? '' : 'AND a.locale_id = :locale_id') . '
                AND a.status = 1
                GROUP BY a.id
                ORDER BY h.updated DESC
        ');

        if (!$this->_ignoreLocales)
            $query->setParams(array_merge($params, ['locale_id' => self::$locale->getCurrentLocaleInfo()->getID()]));
        else
            $query->setParams($params);

        $this->sort($query->execute());
        $this->_hasLoadedArticles = true;
    }

    /**
     * Takes an array of articles and assigns them a certain number of points based on a predefined set of requirements.
     * @param array $articles The array of found articles
     * @return void
     */
    private function sort(array $articles) : void {
        $articlePoints = [];
        $this->_maxPoints = 0;

        $this->_resultCount = count($articles);

        $counter = 0;
        foreach ($articles as $article) {
            if ($counter < $this->_start) {
                $counter++;
                continue;
            }
            else if ($counter >= ($this->_start + $this->_maxResults))
                break;

            $points = 0;

            $titleUpper = strtoupper($article['title']);
            $bodyUpper = strtoupper($article['title']);

            foreach (array_merge($this->_requiredTerms, $this->_terms) as $term) {
                $termUpper = strtoupper($term);

                $points += (substr_count($titleUpper, $termUpper) * 10);
                $points += (substr_count($bodyUpper, $termUpper) * 5);

                if ($titleUpper == $termUpper)
                    $points += 25;
            }

            // If we were the last one to edit this article, add some more points
            $uid = self::$user->getID();
            if ($uid != 0 && $article['user_id'] == $uid)
                $points += 10;

            $articlePoints[] = [
                'id' => $article['id'],
                'title' => $this->markTerms($article['title']) . (self::$user->isRoot() ? ' (' . sprintf(_localized('find.points'), $points) . ')' : ''),
                'href' => self::$config->read('url') . '/index.php/article/' . Sanitizer::addUnderscores($article['title']),
                'body_raw' => $article['body'],
                'body_safe' => $this->_parsedown->setMarkupEscaped(true)->text($article['body']),
                'body_shortened' => $this->markTerms($this->shortenBody($article['body'])),
                'points' => $points
            ];

            $this->_maxPoints = ($points > $this->_maxPoints) ? $points : $this->_maxPoints;

            $counter++;
        }

        uasort($articlePoints, function(array $a, array $b) {
            if (($a['points'] <=> $b['points']) == 0)
                return $b['title'] <=> $a['title'];

            return $b['points'] <=> $a['points'];
        });

        $this->_articles = $articlePoints;
    }

    /**
     * Prepares an array of data that is used by search pages.
     * @return array
     */
    public function prepareData() : array {
        return [
            'action' => self::$config->read('url') . '/index.php/find',
            'articles' => $this->_articles,
            'max_points' => $this->_maxPoints,
            'has_loaded_articles' => $this->_hasLoadedArticles,
            'unparsed_search_terms' => $this->_unparsedSearchTerms,
            'number_of_results' => sprintf(_localized('find.number_of_results'), $this->_resultCount),
            'show_load_more' => $this->_resultCount > $this->_maxResults,
            'max_results' => $this->_maxResults,
            'search_terms' => $this->_unparsedSearchTerms
        ];
    }

    /**
     * Validates search form input
     * @param array $errors An error array
     * @return void
     */
    public function validateInput(array &$errors) : void {
        $inputValidator = new InputValidator($this->_unparsedSearchTerms);

        if ($inputValidator->containsHTML())
            $errors[] = _localized('find.input_contains_html');
        else if ($inputValidator->isTooShort($min = self::$config->read('min_search_term_length')))
            $errors[] = sprintf(_localized('find.input_too_short'), $min);
        else if ($inputValidator->isTooLong($max = self::$config->read('max_search_term_length')))
            $errors[] = sprintf(_localized('find.input_too_long'), $max);

        $this->_errorOccurred = !empty($errors);
    }

    /**
     * Parses the body, removes all HTML and shortens it if necessary.
     * @param string $body The body to shorten
     * @return string The shortened both
     */
    public function shortenBody(string $body) : string {
        if (strlen($body) > ($max = (int) self::$config->read('max_finder_body_length'))) {
            $body = substr($body, 0, $max) . '...';
        }

        return strip_tags($this->_parsedown->setMarkupEscaped(true)->text($body));
    }

    /**
     * Highlights terms in a given text
     * @param string $text The text to highlight
     * @return string The highlighted text
     */
    public function markTerms(string $text) : string {
        $terms = array_merge($this->_terms, $this->_requiredTerms);
        foreach ($terms as $term)
            $text = preg_replace('/(' . $term . ')(?=[^>]*(<|$))/i', '<span class="highlighted">$1</span>', $text);

        return $text;
    }

    /**
     * This method sets the current AJAX request.
     * @param string $request The current request
     */
    public function setRequest(string $request) : void {
        $this->_request = $request;
    }

    /**
     * This method generates JSON based on the current request.
     * @return string The generated JSON
     */
    public function generateJSON() : string {
        switch ($this->_request) {
            case 'getresults':
                $start = $this->_start;
                $end = $start + $this->_maxResults;

                if ($end > $this->_resultCount)
                    $end = $start + ($this->_resultCount - $start);

                $data = [];
                foreach ($this->_articles as $article) {
                    $data[] = [
                        'id' => $article['id'],
                        'title' => $article['title'],
                        'body' => $article['body_shortened'],
                        'href' => $article['href']
                    ];
                }

                return json_encode([
                    'start' => $start,
                    'end' => $end,
                    'total_records' => $this->_resultCount,
                    'data' => $data
                ]);

            default:
                return '';
        }
    }

    /**
     * @return string The page title
     */
    public function getTitle() : string {
        return _localized('find.find_an_article');
    }

    /**
     * @return int The number of terms
     */
    public function getTermCount() : int {
        return count($this->_terms + $this->_requiredTerms + $this->_excludedTerms);
    }

    /**
     * @return string $_unparsedSearchTerms
     */
    public function getUnparsedSearchTerms() : string {
        return $this->_unparsedSearchTerms;
    }

    /**
     * @return array $_terms
     */
    public function getTerms() : array {
        return $this->_terms;
    }

    /**
     * @return array $_requiredTerms
     */
    public function getRequiredTerms() : array {
        return $this->_requiredTerms;
    }

    /**
     * @return array $_excludedTerms
     */
    public function getExcludedTerms() : array {
        return $this->_excludedTerms;
    }

    /**
     * @return array $_articles
     */
    public function getArticles() : array {
        return $this->_articles;
    }

    /**
     * @return int $_maxPoints
     */
    public function getMaxPoints() : int {
        return $this->_maxPoints;
    }
}