<?php
session_start();
?> 

<header>
    <img src="/projet-collectif-r-seau-social-php-cmc-project/popcorn.png" alt="Logo pop screen" id="logo"/>
    <nav id="menu">
        <a href="/projet-collectif-r-seau-social-php-cmc-project/news.php"><img src="/projet-collectif-r-seau-social-php-cmc-project/actu.png" alt="actualites"></a>
        <a href="/projet-collectif-r-seau-social-php-cmc-project/wall.php?user_id=<?php echo $_SESSION['connected_id']; ?>"><img src="/projet-collectif-r-seau-social-php-cmc-project/mur.png" alt="mur"></a>
        <a href="/projet-collectif-r-seau-social-php-cmc-project/feed.php?user_id=<?php echo $_SESSION['connected_id']; ?>"><img src="/projet-collectif-r-seau-social-php-cmc-project/feed.png" alt="feed"></a>
        <a href="/projet-collectif-r-seau-social-php-cmc-project/tags.php?tag_id=1"><img src="/projet-collectif-r-seau-social-php-cmc-project/tags.png" alt="mots cles"></a>
    </nav>
    <nav id="user">
        <a href="#"><img src="/projet-collectif-r-seau-social-php-cmc-project/profil.png" alt="icone de profil"></a>
        <ul>
            <li><a href="/projet-collectif-r-seau-social-php-cmc-project/settings.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Paramètres</a></li>
            <li><a href="/projet-collectif-r-seau-social-php-cmc-project/followers.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Mes suiveurs</a></li>
            <li><a href="/projet-collectif-r-seau-social-php-cmc-project/subscriptions.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Mes abonnements</a></li>
        </ul>
    </nav>
</header>