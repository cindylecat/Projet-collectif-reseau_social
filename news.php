<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Actualités</title> 
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
            echo "<article>";
            echo("Échec de la connexion : " . $mysqli->connect_error);
            echo("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
            echo "</article>";
            exit();
        }
        ?>

        <div id="wrapper">
            
            <aside>
                <img src="avatar.png" alt="Portrait de l'utilisatrice"/>
                
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les derniers messages de
                        tous les pop screeners
                    </p>
                </section>
            </aside>

            <main>
                <?php
                //requête mySQL à la base de données et récupérer ses informations
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name,
                    users.id as author_id,
                    posts_tags.post_id as post_id,
                    posts_tags.tag_id as tag_id,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    LIMIT 5
                    ";
                
                //exécution de la requête mySQL contenue dans la variable $laQuestionEnSql
                include("sources/library.php");
                
                //vérification requête ok
                if ( ! $lesInformations)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                    exit();
                }

                //affiche le résultat de la requête : les derniers posts de tous les utilisateurs du site
                //à chaque tour du while, la variable post ci dessous reçois les informations du post suivant.
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
                            <a href="wall.php?user_id=<?php echo $post["author_id"] ?>"><?php echo $post['author_name'] ?></a>
                        </address>
                        
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>
                        
                        <footer>
                            <small>♥ <?php echo $post['like_number'] ?></small>
                            <a href="tags.php?tag_id=<?php echo $post['tag_id'] ?>">#<?php echo $post['taglist'] ?></a>
                        </footer>
                    </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
