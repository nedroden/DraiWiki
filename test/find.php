<?php
use DraiWiki\src\tools\models\ArticleFinder;

require __DIR__ . '/../src/IndexEmulator.php';

$config = null;
$connection = null;
$errors = [];

start($config);
connectToDatabase($connection);
loadEnvironment();

$articleFinder = new ArticleFinder('test xml');
$articleFinder->parse($errors);
$articleFinder->loadResults();

echo '<strong>Regular terms</strong><ul>';
foreach ($articleFinder->getTerms() as $term)
    echo '<li>', $term, '</li>';

echo '</ul><strong>Required terms:</strong><ul>';

foreach ($articleFinder->getRequiredTerms() as $term)
    echo '<li>', $term, '</li>';

echo '</ul><strong>Excluded terms:</strong><ul>';
foreach ($articleFinder->getExcludedTerms() as $term)
    echo '<li>', $term, '</li>';

if (!empty($errors)) {
    echo '<pre>';
    print_r($errors);
    echo '</pre>';
}