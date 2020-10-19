<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Fotbalu zdar</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="print.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<?php
include "menu.php"
?>

<section id="hero">
    <h1 class="pageHeading">Fotbalu Zdar</h1>
    <div class="button"><a href="#">Vzdělávej se</a></div>
</section>

<section>
    <div class="centeredContentWrapper">
        <h2>Poslední články</h2>
    </div>

    <div id="blogPostList" class="centeredContentWrapper">
        <article class="blogPostPreview">
            <img src="img/yellow_card.png" alt="Žlutá karta">
            <h3>Přestupky za žlutou kartu</h3>
        </article>
        <article class="blogPostPreview">
            <img src="img/red_card.png" alt="Červená karta">
            <h3>Přestupky za červenou kartu</h3>
        </article>
        <article class="blogPostPreview">
            <img src="img/goal.png" alt="Gól">
            <h3>Obecné</h3>
        </article>
    </div>
</section>
<footer>
    <div>
        <span class="footerHeading">Nejčtenější články</span>
        <ul>
            <li><a href="#">ABCD</a></li>
            <li><a href="#">ABCD</a></li>
            <li><a href="#">ABCD</a></li>
            <li><a href="#">ABCD</a></li>
        </ul>
    </div>
    <div>
        <span class="footerHeading">Nejčtenější články</span>
        <ul>
            <li><a href="#">ABCD</a></li>
            <li><a href="#">ABCD</a></li>
            <li><a href="#">ABCD</a></li>
            <li><a href="#">ABCD</a></li>
        </ul>
    </div>
    <div>
        <span class="footerHeading">Nejčtenější články</span>
        <ul>
            <li><a href="#">ABCD</a></li>
            <li><a href="#">ABCD</a></li>
            <li><a href="#">ABCD</a></li>
            <li><a href="#">ABCD</a></li>
        </ul>
    </div>
</footer>
</body>
</html>