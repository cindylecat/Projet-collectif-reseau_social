
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Connexion</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="./style.css"/>
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
                    <h2>Connexion</h2>
                    <?php
                    //traitement du formulaire
                    $enCoursDeTraitement = isset($_POST['email']);
                    if ($enCoursDeTraitement)
                    {
                        //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        
                        $emailAVerifier = $_POST['email'];
                        $passwdAVerifier = $_POST['motpasse'];

                        //sécurité : pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                        $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                        // on crypte le mot de passe pour éviter d'exposer notre utilisatrice en cas d'intrusion dans nos systèmes
                        $passwdAVerifier = md5($passwdAVerifier);
                        //NB: md5 est pédagogique mais n'est pas recommandée pour une vraies sécurité
                        
                        //requête mySQL
                        $lInstructionSql = "SELECT * "
                                . "FROM users "
                                . "WHERE "
                                . "email LIKE '" . $emailAVerifier . "'"
                                ;
                        
                        //vérification de l'utilisateur
                        $res = $mysqli->query($lInstructionSql);
                        $user = $res->fetch_assoc();
                        if ( ! $user OR $user["password"] != $passwdAVerifier)
                        {
                            echo "La connexion a échouée. ";
                        } else
                        {
                            echo "Votre connexion est un succès : " . $user['alias'] . ".";
                            //se souvenir que l'utilisateur s'est connecté pour la suite
                            $_SESSION['connected_id']=$user['id'];
                        }
                    }
                    ?> 

                    <form action="login.php" method="post">
                        <input type='hidden'name='???' value='achanger'>
                        <dl>
                            <dt><label for='email'>E-Mail</label></dt>
                            <dd><input type='email'name='email'></dd>
                            <dt><label for='motpasse'>Mot de passe</label></dt>
                            <dd><input type='password'name='motpasse'></dd>
                        </dl>
                        <input type='submit'>
                    </form>
                    <p>
                        Pas de compte?
                        <a href='registration.php'>Inscrivez-vous.</a>
                    </p>
                </article>
            </main>
        </div>
    </body>
</html>
