<?php
/**
 * Patches HTMLPurifier DOMLex to handle undefined Core.RemoveBlanks / Core.AllowParseManyTags
 * when used with Perfex's xemlock/htmlpurifier-html5 (HTML5Config).
 * Run automatically via composer post-install/update.
 */
$domLex = __DIR__ . '/../vendor/ezyang/htmlpurifier/library/HTMLPurifier/Lexer/DOMLex.php';
if (!is_file($domLex)) {
    return;
}
$content = file_get_contents($domLex);
// Skip if already patched
if (strpos($content, '@$config->get(\'Core.RemoveBlanks\')') !== false) {
    return;
}
$content = str_replace(
    "if (\$config->get('Core.AllowParseManyTags') && defined('LIBXML_PARSEHUGE'))",
    "if (@\$config->get('Core.AllowParseManyTags') && defined('LIBXML_PARSEHUGE'))",
    $content
);
$content = str_replace(
    "if (\$config->get('Core.RemoveBlanks') && defined('LIBXML_NOBLANKS'))",
    "// Core.RemoveBlanks may be undefined when using HTML5Config (e.g. Perfex xemlock/htmlpurifier-html5)\n        if (@\$config->get('Core.RemoveBlanks') && defined('LIBXML_NOBLANKS'))",
    $content
);
file_put_contents($domLex, $content);
