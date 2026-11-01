<?php
/**
 * Ortak sayfa başı. Beklenen değişkenler: $sayfa (ana|api|hakkinda), $baslik, $aciklama.
 */
$e = function ($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
};
$surum = function ($dosya) {
    return @filemtime(__DIR__ . '/../public/' . $dosya) ?: '1';
};
$menu = ['ana' => ['./', 'Oluşturucu'], 'api' => ['api-belgeleri', 'API'], 'hakkinda' => ['hakkinda', 'Hakkında']];
?><!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $e($baslik) ?></title>
<meta name="description" content="<?= $e($aciklama) ?>">
<meta name="theme-color" content="#f2f3f5" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#17181c" media="(prefers-color-scheme: dark)">
<link rel="icon" href="favicon.ico" sizes="32x32">
<link rel="icon" href="favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="apple-touch-icon.png">
<link rel="stylesheet" href="assets/style.css?v=<?= $surum('assets/style.css') ?>">
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5770696223060387" crossorigin="anonymous"></script>
</head>
<body class="sayfa-<?= $e($sayfa) ?>" data-sayfa="<?= $e($sayfa) ?>">
<div class="wrap">
<header class="ust">
<a class="brand" href="./">
<svg class="logo" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3"></rect><circle cx="9" cy="11" r="2"></circle><path d="M6 16c.6-1.5 1.7-2.2 3-2.2s2.4.7 3 2.2"></path><path d="M14.5 10h4"></path><path d="M14.5 13.5h3"></path></svg>
Rastgele Profil
</a>
<nav aria-label="Ana menü">
<ul>
<?php foreach ($menu as $anahtar => [$href, $ad]) : ?>
<li><a href="<?= $href ?>"<?= $anahtar === $sayfa ? ' aria-current="page"' : '' ?>><?= $ad ?></a></li>
<?php endforeach; ?>
</ul>
</nav>
</header>
