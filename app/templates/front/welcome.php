<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Muuska</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--Les styles css-->
        <style>
            body{
                background-color: blue;
            }
            .title{
                margin-left: 1em; font-size: 5em; margin-top: 1em;
            }
            #main{
                display: flex; flex-wrap: wrap; justify-content: center; margin-top: 3em;
            }
            .item{
                width: 40em; height: 18em; padding: 2em; background-color: #eff4f9; border: solid #e3ecf5 1px;
            }
            .underline{
                color: black; text-decoration: underline;
            }
            h3{
                font-size: 1.5em;
            }
            a:hover{
                color: black;
            }
            .list-style-none{
                list-style-type: none;
            }
            .mt{
                margin-top: 2em;
            }
            .text-right{
                text-align: right; padding-right: 5em;
            }
            .nav-item{
                margin: .7em; font-size: 1.2em; text-decoration: underline;
            }
            i{
                margin-right: .5em;
            }
        </style>
    </head>
    <body>
        <!--Login and Register buttons-->
        <?php 
            $user =$item->getExtra('user');
            if(!$user->isLogged()){ ?>
            <nav class="text-right">
                <a class="nav-item text-muted" href="admin/en/login">Log in</a>
                <a class="nav-item text-muted" href="admin/en/register">Register</a>
            </nav>
        <?php } ?>
        <!--Muuska header-->
        <h1 class="title d-inline-block">Muuska</h1>
        <!--Main content-->
        <section id="main">
            <article class="item">
                <h3 class="font-weight-bold"><i class="fa fa-book"></i> <a href="http://muuska.net/fr/" class="underline">Documentation</a></h3>
                <p class="content mt">Muuska est un framework PHP très maniable permettant le développement d’applications Web rapide. Il est construit autour du modèle MVC. Que vous soyez nouveau sur le framework ou bien ayez une expérience passée sur muuska, nous vous recommendons de lire la documentaiton complète du début à la fin.</p>
            </article>
            <article class="item">
                <h3 class="font-weight-bold"><i class="fa fa-eye"></i> <span class="underline">Fonctionnalités</span></h3>
                <p class="content">
                    <ul class="list-style-none">
                        <li>Système de génération de contenu HTML (Formulaires, tableaux, lien, arbre, éditeur de texte enrichie)</li>
                        <li>Système d’upload sophistiqué pouvant uploader des fichiers de grandes capacités peu importe les limites du serveur (upload_max_filesize)</li>
                        <li>Support du multilingue</li>
                        <li>Support des modules</li>
                        <li>Support des thèmes</li>
                        <li>Implémentation du Back office</li>
                    </ul>
                </p>
            </article>
            <article class="item">
                <h3 class="font-weight-bold"><i class="fa fa-bell"></i> <a href="#" class="underline">Muuska News</a></h3>
                <p class="content mt">Muuska news est une plateforme et une newsletter publiant les notifications au sujet des dernières modifications et plus importantes informations dans l'ecosystème de muuska, y compris le versionning du framework.</p>
            </article>
            <article class="item">
                <h3 class="font-weight-bold"><i class="fa fa-plus"></i> <span class="underline">Avantages</span></h3>
                <p class="content">
                    <ul class="list-style-none">
                        <li>Framework purement orienté objet</li>
                        <li>Routage URI flexible et simple</li>
                        <li>Séparation entre les traitements et le support d’accès aux données</li>
                        <li>Manipulation facile des templates</li>
                        <li>Code réutilisable et plus facile à maintenir</li>
                        <li>Mise en place d’un système permettant de faire les opérations CRUD (Create, Read, Update, Delete)</li>
                        <li>...</li>
                    </ul>
                </p>
            </article>
        </section>
    </body>
</html>