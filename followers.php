
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnés </title> 
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
        
        <div id="wrapper">    

            <aside>
                <img src = "avatar.png" alt = "Portrait de l'utilisatrice"/>
                
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des pop screeners qui
                        vous suivent
                        <!-- n° <?php //echo intval($_GET['user_id']) ?></p> -->
                </section>
            </aside>

            <main class='contacts'>
                <?php
                //récupérer l'id de l'utilisateur
                $userId = intval($_GET['user_id']);

        
                //sélectionne les followers de l'utilisateur et récupère leurs données
                $laQuestionEnSql = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$userId'
                    GROUP BY users.id
                    ";
                
                //exécution de la requête mySQL contenue dans la variable $laQuestionEnSql
                include("sources/library.php");
                
                //affiche le résultat de la requête : les followers de l'utilisateur
                while ($followers = $lesInformations->fetch_assoc())
                {?>
                    <article>
                        <img src="avatar.png" alt="blason"/>
                        <h3><a href="wall.php?user_id=<?php echo $followers["id"] ?>"><?php echo $followers["alias"] ?></a></h3>
                        <p><?php echo $followers["id"] ?></p> 
                    </article>   
                <?php } ?>
            </main>
        </div>
    </body>
</html>
