# Symfony Repo

Install Symfony:
`composer create-project symfony/skeleton my_project_name composer create-project symfony/website-skeleton my_project_name`

Install Package from [Symfony Recipes Server](https://flex.symfony.com/)

Run simple symfony server:
`symfony server:start`

Symfony Command:

- Generate Controller: php bin/console make:controller
- Generate Model: php bin/console make:entity
- Create migration: php bin/console make:migration
- Excute migration: php bin/console doctrine:migration:migrate
- Create Database: php bin/console doctrine:database:create

Package List:

- Generate Command Package: symfony/maker-bundle --dev
- Template Package: twig
- Database package: doctrine/orm, doctrine/doctrine-bundle, migration
- Doctrine Package:
  - doctrine/annotations
