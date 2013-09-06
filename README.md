r0mdauDb
========

Modèle de données NoSQL codé en PHP, données au format JSON

Des exemples d'utilisation de cette classe seront présentés très prochainement.

# Exemples d'utilisation

## Initialisation de la classe :
`$database = new r0mdauDb($directory);` 
$directory contient le chemin vers le répertoire où seront stockés les fichiers du modèle de données.

## Pour accéder à une table :
`$database->table($file);` 
$file contient le nom de la table que l'on souhaite requêter.
Retourne un objet intermédiaire **r0mdauTable**

## Pour requêter une table :

### Méthode de sélection :
`$result = $database->table($file)->find();`
Retourne tous les éléments de la table dans un **array()** d'objets

`$result = $database->table($file)->find(array("nom"=>"dauby"));`
Retourne tous les éléments de la table qui ont un attribut `nom` avec `dauby` comme valeur dans un **array()** d'objets

`$result = $database->table($file)->find1(array("id"=>19));`
Retourne le premier élément (si plusieurs) qui a un attribut `id`avec 19 comme valeur sous forme d'objet

La suite bientôt.
