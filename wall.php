
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mur</title> 
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
            //Le mur concerne un utilisateur en particulier
            //$userId =intval($_GET['user_id']);
            
            if (isset($_GET['user_id'])){
                $user_wall_id =intval($_GET['user_id']);
            }
            else{

                $user_wall_id =$_SESSION['connected_id'];
            }
            //echo "connectedID = " . $user_connectedID; 
            //echo "userID = " . $userId;
            ?>

            <aside>
                <?php
                 //sélectionner dans la table users, l'utilisateur connecté             
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$user_wall_id' ";
                
                //exécution de la requête mySQL contenue dans la variable $laQuestionEnSql
                include("sources/library.php");

                $user = $lesInformations->fetch_assoc();
                //echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>

                <img src="avatar.png" alt="Portrait du pop screener connecté"/>
                
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les messages de <?php echo $user["alias"] ?>
                        <!-- (n° <?php //echo $user_wall_id ?>) -->
                    </p>
                </section>
                
                <article>
                    <?php
                    //echo "<pre>" . print_r($_POST, 1) . "</pre>";

                    //s'abonner à un pop screener
                    $enCoursDeTraitement = isset($_POST['jeVeuxTeSuivre']);
                    if ($enCoursDeTraitement)
                    {
                        $followed_connectedID = $_POST['jeVeuxTeSuivre'];
                        $following_connectedID = $_POST['tuMeSuis'];

                        //$user_connectedID = intval($mysqli->real_escape_string($user_connectedID));
                        
                        //requête my SQL
                         $lInstructionSql = "INSERT INTO followers "
                                . "(id, followed_user_id, following_user_id) "
                                . "VALUES (NULL, "
                                . $followed_connectedID . ", "
                                . "'" . $following_connectedID . "' )";

                        //exécution de la requête
                        $ok = $mysqli->query($lInstructionSql);
                        if (! $ok)
                        {
                            echo "Impossible de suivre ce pop screener." . $mysqli->error;
                        } else
                        {
                            echo "Cool un nouveau pop screen friend!";
                        }
                    }

                    if (isset($_GET['user_id']))
                    {?>
                        <form action="wall.php" method="post">
                            <input type='hidden' name='tuMeSuis' value="<?php echo $_SESSION['connected_id']?>"> 
                            <input type='hidden' name='jeVeuxTeSuivre' value="<?php echo $_GET['user_id']?>">
                            <input type='submit' value="Abonne-toi  &#127909"></input> 
                         </form> 
                    <?php } ?>       
                </article>
            </aside>

            <main>
                <?php
                //récupérer tous les messages de l'utilisatrice
                $laQuestionEnSql = "
                    SELECT posts.content, 
                    posts.created, 
                    users.alias as author_name, 
                    posts_tags.post_id as post_id,
                    posts_tags.tag_id as tag_id,
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$user_wall_id' 
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
                
                //affiche le résultat de la requête : les posts de l'utilisatrice
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
                            <?php echo $post["author_name"] ?>
                        </address>
                        
                        <div>
                            <p><?php echo $post["content"] ?></p>
                        </div>

                        <footer>
                            <small>♥ <?php echo $post["like_number"] ?></small>
                            <a href="tags.php?tag_id=<?php echo $post['tag_id'] ?>">
                            #
                            <?php echo $post["taglist"] ?>
                            </a>
                        </footer>
                    </article>
                <?php } ?>
                
                    <article>
                        <h2>Poster un message</h2>
                        <?php
                        //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        $enCoursDeTraitement = isset($_POST['author_id']);
                        if ($enCoursDeTraitement)
                        {
                            $user_connectedID = $_POST['author_id'];
                            $postContent = $_POST['message'];
                            $user_connectedID = intval($mysqli->real_escape_string($user_connectedID));
                            $postContent = $mysqli->real_escape_string($postContent);
                            
                            //requête mySQL
                            $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created) "
                                . "VALUES (NULL, "
                                . $user_connectedID . ", "
                                . "'" . $postContent . "', "
                                . "NOW())";

                            //exécution de la requête mySQL
                            $ok = $mysqli->query($lInstructionSql);
                            if (! $ok)
                            {
                                echo "Impossible d'ajouter le message: " . $mysqli->error;
                            } else
                            {
                                echo "Message posté en tant que :" . $user_wall_id;
                            }
                        }
                        ?>                     
                    
                    <form action="wall.php" method="post">
                        <input type='hidden' name='author_id' value="<?php echo $user_wall_id ?>">
                        <dl>
                            <dt><label for='message'></label></dt>
                            <dd><textarea name='message'></textarea></dd>
                        </dl>
                        <input type='submit'>
                    </form>               
                </article>
            </main>
        </div>
    </body>
</html>
