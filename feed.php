
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Flux</title>         
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
                <?php
                //Le mur concerne un utilisateur en particulier
                $userId = intval($_GET['user_id']);
                $user_connectedID = $_SESSION['connected_id'];
                //echo "userID = " . $userId;
                //echo "connectedID = " . $user_connectedID; 
                
                //sélectionner toutes les colonnes dans la table users, de l'utilisateur connecté
                $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
                
                //exécution de la requête mySQL contenue dans la variable $laQuestionEnSql
                include("sources/library.php");
                
                $user = $lesInformations->fetch_assoc();
                //echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>

                <img src="avatar.png" alt="Portrait de l'utilisatrice"/>
                
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message des pop screeners que vous suivez,
                        <a href="wall.php?user_id=<?php echo $user["id"] ?>"><?php echo $user["alias"] ?></a>
                        <!-- n° <?php //echo $userId ?> -->
                    </p>
                </section>
            </aside>
            
            <main>
                <?php
                //récupérer tous les messages des utilisateurs auxquel est abonné l'utilisateur connecté
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name, 
                    users.id as author_id, 
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM followers 
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE followers.following_user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                
                //exécution de la requête mySQL contenue dans la variable $laQuestionEnSql
                include("sources/library.php");
                
                //vérification requête ok
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                //affiche le résultat de la requête : les posts du flux
                while ($post = $lesInformations->fetch_assoc())
                {
                //echo "<pre>" . print_r($post, 1) . "</pre>";
                ?>                
                    <article>
                        <h3>
                            <?php
                            //affiche la date de création du post
                            $date =new DateTime($post['created']); 
                            //strftime('%d-%m-%Y',strtotime($date));
                            ?>
                            <time><?php echo $date->format('l jS \o\f F Y h:i:s A'), "\n";?></time>
                        </h3>
                        
                        <address> 
                            De 
                            <a href="wall.php?user_id=<?php echo $post["author_id"] ?>">
                            <?php echo $post["author_name"] ?>
                            </a>
                        </address>
                        
                        <div>
                            <p><?php echo $post["content"] ?></p>
                        </div>

                        <footer>
                            <small>♥ <?php echo $post["like_number"] ?></small>
                            <a href="tags.php?tag_id=<?php echo $post['taglist'] ?>">
                            #
                            <?php echo $post["taglist"] ?>
                            </a>
                        </footer>
                    </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
