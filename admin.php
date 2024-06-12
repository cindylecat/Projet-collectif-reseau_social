
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Administration</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>

        <?php
            //ajout du header
            include("sources/header.php");

            //connexion à la base de donnée MySQL
            include("sources/connexion.php");

            //vérification connexion ok
            if ($mysqli->connect_errno)
            {
            echo("Échec de la connexion : " . $mysqli->connect_error);
            exit();
            }
        ?>

        <div id="wrapper" class='admin'>
            
            <aside>
                <h2>Mots-clés</h2>
                
                <?php
                //sélectionner toutes les colonnes de la table tags et limité le résultat à 50 lignes
                $laQuestionEnSql = "SELECT * FROM `tags` LIMIT 50";
                
                //exécution de la requête mySQL contenue dans la variable $laQuestionEnSql
                include("sources/library.php");
                
                //vérification requête ok
                if (! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                }
                
                //affiche le résultat de la requête
                while ($tag = $lesInformations->fetch_assoc())
                {
                //echo "<pre>" . print_r($tag, 1) . "</pre>";
                ?>
                    <article>
                        <h3>#<?php echo $tag["label"] ?></h3>
                        <p><?php echo $tag["id"]?></p>
                        <nav>
                            <a href="tags.php?tag_id=<?php echo $tag["id"]?>">Messages</a>
                        </nav>
                    </article>
                <?php } ?>
            </aside>

            <main>
                <h2>Pop Screeners</h2>
                
                <?php
                //sélectionner toutes les colonnes de la table users et limité le résultat à 50 lignes
                $laQuestionEnSql = "SELECT * FROM `users` LIMIT 50";
                
                //exécution de la requête mySQL contenue dans la variable $laQuestionEnSql
                include("sources/library.php");
                
                //vérification requête ok
                if (! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                    exit();
                }
                
                //affiche le résultat de la requête
                while ($lesUtilisatrices = $lesInformations->fetch_assoc())
                {
                //echo "<pre>" . print_r($tag, 1) . "</pre>";
                ?>
                    <article>
                        <h3><?php echo $lesUtilisatrices["alias"] ?></h3>
                        <p><?php echo $lesUtilisatrices["id"]?></p>
                        <nav>
                            <a href="wall.php?user_id=<?php echo $lesUtilisatrices["id"]?>">Mur</a>
                            <a href="feed.php?user_id=<?php echo $lesUtilisatrices["id"]?>">Flux</a>
                            <a href="settings.php?user_id=<?php echo $lesUtilisatrices["id"]?>">Paramètres</a>
                            <a href="followers.php?user_id=<?php echo $lesUtilisatrices["id"]?>">Suiveurs</a>
                            <a href="subscriptions.php?user_id=<?php echo $lesUtilisatrices["id"]?>">Abonnements</a>
                        </nav>
                    </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
