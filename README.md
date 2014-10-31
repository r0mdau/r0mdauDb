r0mdauDb
========

Modèle de données NoSQL codé en PHP, données au format JSON

#Définitions
Chaque enregistrement possède un identifiant unique dénommé `_rid`.
Chaque table est représentée physiquement par un fichier.
Les fichiers sont stockés dans un seul répertoire de travail. La class r0mdauDb est instanciée avec le chemin vers ce répertoire en paramètre.

Attention ! Les accès concurrents ne sont pas gérés.


# Exemples d'utilisation

## Initialisation de la classe :
```php
$database = new r0mdauDb($directory);
```
$directory contient le chemin vers le répertoire où seront stockés les fichiers du modèle de données.

## Pour accéder à une table :
```php
$database->table($file);
```
$file contient le nom de la table que l'on souhaite requêter.
Retourne un objet intermédiaire **r0mdauTable**

## Pour requêter une table :

**PREAMBULE :**
Chaque méthode retourne un booleen, `true` si l'action s'est bien passée, `false` dans le cas contraire

### Méthode d'insertion :
```php
$result = $database->table($file)->insert(array("nom" => "dauby", "prenom" => "romain"));
```
Insère le tableau dans la table spécifiée au format json et attribue un identifiant `_rid` unique à l'enregistrement

### Méthode de suppression :
```php
$result = $database->table($file)->delete(array("nom" => "dauby"));
```
Supprime tous les éléments de la table qui ont un attribut `nom` avec `dauby` comme valeur

```php
$result = $database->table($file)->delete(array("_rid" => $rid));
```
Supprime le seul élément de la table qui a pour valeur $rid à l'attribut `_rid`

### Méthode de sélection :
```php
$result = $database->table($file)->find();
```
Retourne tous les éléments de la table dans un **array()** d'objets

```php
$result = $database->table($file)->find(array("nom" => "dauby"));
```
Retourne tous les éléments de la table qui ont un attribut `nom` avec `dauby` comme valeur dans un **array()** d'objets

```php
$result = $database->table($file)->find1(array("id" => 19));
```
Retourne le premier élément qui a un attribut `id` avec `19` comme valeur sous forme d'objet

### Méthode de modification :
Pour la modification, l'enregistrement est entièrement remplacé par le second **array()** passé en paramètre.

```php
$result = $database->table($file)->update(array("prenom" => "romain"), array("prenom" => "georges", "nom" => "lucas"));
```
Modifie tous les éléments de la table qui ont un attribut `prenom` avec `romain` comme valeur.

```php
$result = $database->table($file)->update(array("_rid" => $rid), array("prenom" => "georges", "nom" => "lucas"));
```
Modifie le seul élément de la table qui a pour valeur $rid à l'attribut `_rid`