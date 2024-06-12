<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnements</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <?php
        //ajout du header
        include("sources/header.php");
        
        //connexion à la base de donnée MySQL
        include("sources/connexion.php");
        ?>

        <div id="wrapper">

            <aside>
                <img src="avatar.png" alt="Portrait de l'utilisatrice"/>
                
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des pop screeners que
                        vous suivez
                        <!-- n° <?php //echo intval($_GET['user_id']) ?> -->
                    </p>
                </section>
            </aside>
            
            <main class='contacts'>
                <?php
                //récupère l'id de l'utilisateur via l'URL puis le stocke dans la variable
                $userId = intval($_GET['user_id']);
                
                //requête mySQL à la base de données: récupère le nom de l'utilisateur
                $laQuestionEnSql = "
                    SELECT users.* 
                    FROM followers 
                    LEFT JOIN users ON users.id=followers.followed_user_id 
                    WHERE followers.following_user_id='$userId'
                    GROUP BY users.id
                    ";
                //exécution de la requête mySQL contenue dans la variable $laQuestionEnSql
                include("sources/library.php");
                
                //affiche le résultat de la requête : la liste des personnes dont l'utilisatrice suit les messages
                while ($subscript = $lesInformations->fetch_assoc())
                {?>
                    <article>
                        <img src="avatar.png" alt="blason"/>
                        <h3><a href="wall.php?user_id=<?php echo $subscript["id"]?>"><?php echo $subscript["alias"] ?></a></h3>
                        <p><?php echo $subscript["id"] ?></p> 
                    </article>                
                <?php } ?>
            </main>
        </div>
    </body>
</html>
