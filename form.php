<?php
// vérifie la possibilité d'effectuer des requêtes distantes
$error = false;
if (!file_get_contents("http://www.leboncion.fr")) {
    $error = true;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Flux RSS des annonces Leboncoin</title>
    </head>
    <body>
        <h1>Flux RSS des annonces Leboncoin</h1>
        <?php if ($error) : ?>
        <p style="width: 600px; font-weight: bold; color: #EF0000;">
            Les connexions distantes ne semblent pas actives sur cet hébergement.
            Il ne sera pas possible de générer les flux RSS.
        </p>
        <?php endif; ?>
        <form action="" method="get" style="width: 600px;">
            <fieldset>
                <legend>Génération d'un flux RSS</legend>
                <p>
                    <label for="url">
                        Indiquer l'adresse de recherche Leboncoin<br />
                        <input type="text" id="url" name="url" value="" size="75" />
                        <input type="submit" value="RSS" />
                    </label>
                </p>
            </fieldset>
        </form>
    </body>
</html>