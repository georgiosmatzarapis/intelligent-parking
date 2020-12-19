# project-web

## Repository Structure
The repository consists of the following top level folders and files
-  [folder][project-implementation](project-implementation): contains the implementation of the project
-  [folder][project-wording](project-wording): contains the wording of the project as well as all the given files
-  [file][.gitignore](.gitignore): global gitignore file
-  [file][README.md](README.md): this file

## [folder] project-implementation
Under this folder the structure is as follows
- [folder] [my-sql](project-implementation/my-sql): 
   contains files related to the initial database creation.
  -  [file] [database-model.mwb](project-implementation\my-sql\database-model.mwb): 
     MySQL Workbench file. (Make sure to set the correct MySQL version under Workbench for the generation of the code to work corectly.)
___

- [folder] [other-files](project-implementation/other-files): 
   contains random files needed for the implementation.
  -  [file] [JSON-represenation-of-php-obj-of-parsed-KML.json](project-implementation\other-files\JSON-represenation-of-php-obj-of-parsed-KML.json)

___


- [folder] [project-web](project-implementation/project-web): 
  this folder maps as is to `/var/www/` on the vm spawn by the vagrant.
  -  [folder] [public_html](project-implementation/project-web/public_html): 
     Document Root, all public files are here.
     -  [folder] [api](project-implementation/project-web/public_html/api): 
        Contains php files that create the api.
           - [file] [demand_curves.php](project-implementation/project-web/public_html/api/demand_curves.php): 
             When called, this file shows a JSON array that contains the demand curve types availiable.
     -  [folder] [css](project-implementation/project-web/public_html/css): 
        Contains the css files.
           - [file] [main.css](project-implementation/project-web/public_html/css/main.css): 
             Contains the css classes that are used all over the place.
     -  [folder] [errors](project-implementation/project-web/public_html/errors): 
        Contains php files that are used in common server error overide.
           - [file] [forbidden.php](project-implementation/project-web/public_html/errors/forbidden.php): 
             File that renders a page instead of the default apache 403 forbidden error.
           - [file] [not_found.php](project-implementation/project-web/public_html/errors/not_found.php): 
             File that renders a page instead of the default apache 404 not found error.
     -  [folder] [img](project-implementation/project-web/public_html/img): 
        Contains all images.
        -  [folder] [content](project-implementation/project-web/public_html/img/content): 
           Content images. 
        -  [folder] [layout](project-implementation/project-web/public_html/img/layout): 
           Layout images.
           -  [file] [favicon.png](project-implementation/project-web/public_html/img/layout/favicon.png)
     -  [folder] [js](project-implementation/project-web/public_html/js): 
        Contains setting javascript files.
           - [file] [main.js](project-implementation/project-web/public_html/js/main.js): 
             This file contains javascript code that should be on all pages.
     -  [file] [info.php](project-implementation/project-web/public_html/info.php): 
        PHP info for debugging reasons.
     -  [file] [index.php](project-implementation/project-web/public_html/index.php): 
        Is the index page.
  -  [folder] [resources](project-implementation/project-web/resources): 
     Contains our and 3rd party libraries, configs and other code.
     -  [folder] [library](project-implementation/project-web/resources/library): 
        Contains all our and 3rd party libraries.
          -  [file] [templateFunctions.php](project-implementation/project-web/resources/templates/templateFunctions.php)
     -  [folder] [templates](project-implementation/project-web/resources/templates): 
        Contains reusable material that construct our pages.
        -  [file] [error.php](project-implementation/project-web/resources/templates/error.php)
        -  [file] [footer.php](project-implementation/project-web/resources/templates/footer.php)
        -  [file] [header.php](project-implementation/project-web/resources/templates/header.php)
        -  [file] [home.php](project-implementation/project-web/resources/templates/home.php)
     -  [file] [config.php](project-implementation/project-web/resources/config.php): 
        This is the main configuration file. It should be included in every (or most) php page.

___


- [folder] [vagrant](project-implementation/vagrant): 
  contains necessary files to create the vm.
  -  [file][.gitignore](project-implementation/vagrant.gitignore):
     vagrant  gitignore file
  -  [file] [001-project-web.conf](project-implementation/vagrant/001-project-web.conf): 
     Apache configuration file.
  -  [file] [startupScript.sh](project-implementation/vagrant/startupScript.sh): 
     Executed upon vm creation.
  -  [file] [updateConf.sh](project-implementation/vagrant/updateConf.sh): 
     Should be executed every time there is a change in the conf file after vm provisioning.
  -  [file] [Vagrantfile](project-implementation/vagrant/Vagrantfile): 
     Contains setting for the vm vagrant creates.
___

