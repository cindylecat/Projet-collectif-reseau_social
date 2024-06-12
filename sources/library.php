<?php

    /*
    exécution de la requête SQL contenue dans la variable $laQuestionEnSql 
    à l'aide de l'objet $mysqli et 
    stockage du résultat dans la variable $lesInformations
    */
    $lesInformations = $mysqli->query($laQuestionEnSql);

?>