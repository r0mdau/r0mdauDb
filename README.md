r0mdauDb
========

Modèle de données NoSQL codé en PHP, données au format JSON

#Définitions
Chaque document possède un identifiant unique dénommé `_rid`.
Chaque collection est représentée physiquement par un fichier.
Les fichiers sont stockés dans un seul répertoire de travail. La class r0mdauDb est instanciée avec le chemin vers ce répertoire en paramètre.

Attention ! Les accès concurrents ne sont pas gérés.


# Exemples d'utilisation

## Initialisation de la classe :
```php
$database = new r0mdauDb($directory);
```
$directory contient le chemin vers le répertoire où seront stockés les fichiers du modèle de données.

## Pour accéder à une collection :
```php
$database->collection($file);
```
$file contient le nom de la collection que l'on souhaite requêter.
Retourne un objet intermédiaire **r0mdauCollection**

## Pour requêter une collection :

**PREAMBULE :**
Chaque méthode retourne un booleen, `true` si l'action s'est bien passée, `false` dans le cas contraire

### Méthode d'insertion :
```php
$result = $database->collection($file)->insert(array("nom" => "dauby", "prenom" => "romain"));
```
Insère le tableau dans la collection spécifiée au format json et attribue un identifiant `_rid` unique au document

### Méthode de suppression :
```php
$result = $database->collection($file)->delete(array("nom" => "dauby"));
```
Supprime tous les documents de la collection qui ont un attribut `nom` avec `dauby` comme valeur

```php
$result = $database->collection($file)->delete(array("_rid" => $rid));
```
Supprime le seul document de la collection qui a pour valeur $rid à l'attribut `_rid`

### Méthode de sélection :
```php
$result = $database->collection($file)->find();
```
Retourne tous les documents de la collection dans un **array()** d'objets

```php
$result = $database->collection($file)->find(array("nom" => "dauby"));
```
Retourne tous les documents de la collection qui ont un attribut `nom` avec `dauby` comme valeur dans un **array()** d'objets

```php
$result = $database->collection($file)->find1(array("id" => 19));
```
Retourne le premier document qui a un attribut `id` avec `19` comme valeur sous forme d'objet

### Méthode de modification :
Pour la modification, les documents concernés sont entièrement remplacés par le second **array()** passé en paramètre.

```php
$result = $database->collection($file)->update(array("prenom" => "romain"), array("prenom" => "georges", "nom" => "lucas"));
```
Modifie tous les documents de la collection qui ont un attribut `prenom` avec `romain` comme valeur.

```php
$result = $database->collection($file)->update(array("_rid" => $rid), array("prenom" => "georges", "nom" => "lucas"));
```
Modifie le seul document de la collection qui a pour valeur $rid à l'attribut `_rid`