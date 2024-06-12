<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Inscription</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <?php
        //ajout du header
        include("../sources/header.php");

        //connexion à la base de donnée MySQL
        include("../sources/connexion.php");
        ?>

        <div id="wrapper" >

            <aside>
                <h2>Bienvenue chez PopScreen</h2>
                <p>Un réseau social dédié aux amateurs de films et de séries, 
                en mettant l'accent sur la communauté, le partage et la découverte.</p>
            </aside>
            
            <main>
                <article>
                    <h2>Inscription</h2>
                    <?php
                    //traiter le formulaire
                    $enCoursDeTraitement = isset($_POST['email']);
                    if ($enCoursDeTraitement)
                    {
                        //récupérer ce qu'il y a dans le formulaire
                        //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        $new_email = $_POST['email'];
                        $new_alias = $_POST['pseudo'];
                        $new_passwd = $_POST['motpasse'];

                        //sécurité : pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $new_email = $mysqli->real_escape_string($new_email);
                        $new_alias = $mysqli->real_escape_string($new_alias);
                        $new_passwd = $mysqli->real_escape_string($new_passwd);
                        // on crypte le mot de passe pour éviter d'exposer notre utilisatrice en cas d'intrusion dans nos systèmes
                        $new_passwd = md5($new_passwd);
                        //NB: md5 est pédagogique mais n'est pas recommandée pour une vraies sécurité
                        
                        //requête mySQL
                        $lInstructionSql = "INSERT INTO users (id, email, password, alias) "
                                . "VALUES (NULL, "
                                . "'" . $new_email . "', "
                                . "'" . $new_passwd . "', "
                                . "'" . $new_alias . "'"
                                . ");";
                        
                        //exécution de la requête
                        $ok = $mysqli->query($lInstructionSql);
                        if (! $ok)
                        {
                            echo "L'inscription a échouée : " . $mysqli->error;
                        } else
                        {
                            echo "Votre inscription est un succès : " . $new_alias;
                            echo " <a href='login.php'>Connectez-vous.</a>";
                        }
                    }
                    ?>                     
                    
                    <form action="registration.php" method="post">
                        <!-- garde en mémoire les informations de l'utilisateur -->
                        <input type='hidden'name='???' value='achanger'>
                        <dl>
                            <dt><label for='pseudo'>Pseudo</label></dt>
                            <dd><input type='text'name='pseudo'></dd>
                            <dt><label for='email'>E-Mail</label></dt>
                            <dd><input type='email'name='email'></dd>
                            <dt><label for='motpasse'>Mot de passe</label></dt>
                            <dd><input type='password'name='motpasse'></dd>
                        </dl>
                        <input type='submit'>
                    </form>
                </article>
            </main>
        </div>
    </body>
</html>
