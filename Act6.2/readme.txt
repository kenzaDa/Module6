les commandes qu'il faut lancer pour tester le projet

1 - composer install	

2- php bin/console doctrine:database:create

3 - php bin/console doctrine:migrations:migrate

4- php bin/console doctrine:fixtures:load

5- php bin/console server:run

 Ouvrir Postman pou tester les routes:
 
 - http://127.0.0.1:8000/articles avec la methode GET pour récuperer tous les articles

 - http://127.0.0.1:8000/article/{id} avec la methode GET pour récuperer  l'article avec id bien défini

 - http://127.0.0.1:8000/article avec la methode POST pour insérer un article conforme à celui passé via le body de la requete de base

 - http://127.0.0.1:8000/article/{id} avec la methode PUT pour insérer ou modifier un article conforme à celui passé via le body de la requete de base

 - http://127.0.0.1:8000/lastarticle avec la methode GET Pour récupérer les 3 derniers articles.

 - http://127.0.0.1:8000/article/{id} avec la methode DELETE Pour supprimer un article.


pour teqter l'API créer dans le projet 6.1 il faut lancer le projet 6.1
les commandes qu'il faut lancer pour tester le projet
1- composer install
2- composer require server
3-php bin/console server:run
 tester pour POSTMAN 
"/articles" pour afficher tous les articles en methode GET
"/article/{id}" pour récupérer l’article avec {id} en methode GET
"/addArticle" en methode POST pour ajouter un article
"/ModifArticle" en methode PUT pour modifier un article
"/delete" en methode DELETE pour supprimer un article