<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Les message par mot-clé</title> 
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
            <?php
             //Le mur concerne un mot-clé en particulier
            $tagId = intval($_GET['tag_id']);
            ?>

            <aside>
                <?php
                //sélectionner toutes les colonnes dans la table tags où la colonne id est égale à la valeur de $tagId.
                $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";
                
                //exécution de la requête mySQL contenue dans la variable $laQuestionEnSql
                include("sources/library.php");
                
                $tag = $lesInformations->fetch_assoc();
                //echo "<pre>" . print_r($tag, 1) . "</pre>";
                ?>

                <img src="avatar.png" alt="Portrait de l'utilisatrice"/>
                
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les derniers messages comportant
                        le mot-clé <?php echo $tag["label"] ?>
                        (n° <?php echo $tagId ?>)
                    </p>

                </section>
            </aside>
            
            <main>
                <?php
                //récupérer tous les messages avec un mot clé donné
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name,
                    users.id as author_id,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts_tags as filter 
                    JOIN posts ON posts.id=filter.post_id
                    JOIN users ON users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE filter.tag_id = '$tagId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                
                //exécution de la requête mySQL contenue dans la variable $laQuestionEnSql
                include("sources/library.php");;
               
                //vérification requête ok
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

               //affiche le résultat de la requête : les tags
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
                            <a href="wall.php?user_id=
                            <?php echo $post["author_id"]?>">
                            <?php echo $post["author_name"] ?>
                            </a>
                        </address>
                        
                        <div>
                            <p><?php echo $post["content"] ?></p>
                        </div>                                            
                        
                        <footer>
                            <small>♥ <?php echo $post["like_number"] ?></small>
                            <a href="">#<?php echo $post["taglist"] ?></a>
                        </footer>
                    </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>