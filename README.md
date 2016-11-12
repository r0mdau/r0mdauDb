r0mdauDb
========

Client pour travailler sur un modèle de données NoSQL codé en PHP, données stockées au format JSON.

**Les accès concurrents sont gérés !**

#Définitions

1. Chaque document possède un identifiant unique dénommé `_rid`.
2. Chaque collection est représentée physiquement par un fichier.
3. Les fichiers sont stockés dans un seul répertoire de travail. La class r0mdauDb est instanciée avec le chemin vers ce répertoire en paramètre.

# Exemples d'utilisation

## Initialisation de la classe :

`$directory` contient le chemin vers le répertoire où seront stockés les fichiers (=collections) du modèle de données.

```php
$database = new r0mdauDb($directory);
```

## Pour accéder à une collection :

`$nom` contient le nom de la collection que l'on souhaite requêter. Exemple : `user`

Retourne un objet **r0mdauCollection**

```php
$database->collection($nom);
```

## Pour requêter une collection :

**PREAMBULE :**
Les méthodes Insert, Update, Delete retournent un booleen, `true` si l'action s'est bien passée, `false` dans le cas contraire

### Méthode d'insertion :

Insère le tableau dans la collection spécifiée au format json et attribue un identifiant `_rid` unique au document 
si celui-ci n'a pas été spécifié.

```php
$successBool = $database->collection($file)->insert(
  [
    "nom" => "dauby", 
    "prenom" => "romain"
  ]
);
```

### Méthode de suppression :

*Il faut au préalable rechercher tous les documents à effacer pour fournir l'attribut `_rid` à la méthode delete*

Supprime le seul document de la collection qui a pour valeur `$rid` à l'attribut `_rid`

```php
$successBool = $database->collection($file)->delete($rid);
```

### Méthode de sélection :

Retourne tous les documents de la collection dans un **array()** d'objets


```php
$results = $database->collection($file)->find();
```

Retourne tous les documents de la collection qui ont un attribut `nom` avec `dauby` comme valeur dans un **array()** d'objets

```php
$results = $database->collection($file)->find(
  [
    "nom" => "dauby"
  ]
);
```

Retourne le premier document qui a un attribut `nom` avec `19` comme valeur sous forme d'objet

```php
$result = $database->collection($file)->findOne(
  [
    "nom" => 19
  ]
);
```

### Méthode de modification :

Pour la modification, les documents concernés sont entièrement remplacés par le second **array()** passé en paramètre.

*Il faut au préalable rechercher tous les documents à effacer pour fournir l'attribut `_rid` à la méthode delete*

Modifie le seul document de la collection qui a pour valeur $rid à l'attribut `_rid`

```php
$successBool = $database->collection($file)->update(
  $rid,
  [
    "prenom" => "georges", 
    "nom" => "lucas"
  ]
);
```