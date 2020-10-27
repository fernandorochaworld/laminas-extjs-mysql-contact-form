# Laminas - ExtJS - MySQL - Contact List Application

## Notes
- Laminas 3.1.1 / ExtJS 3.4 / MySQL is used to build a Contact List, with data being saved to Laminas endpoints.
  

## Install
- `composer install`
- create a `laminas-extjs-mysql-contact-form` MySQL db, configured in `config/autoload/global.php`
- import the `data/dump-laminas-extjs-mysql-contact-form.sql`
- serve with `php -S 0.0.0.0:8080 -t public public/index.php`
- Visit `http://localhost:8080/` after serving the app to test.
  

## Authentication and ACL Process
The Authentication of the user and ACL process are based on the role of the user logged in the system.
- There is a login page, a page to manage the Contact List and a page to manage the data of the Users.
- Based on the role of the user the system permits the user to use the pages, if no user is logged into the system all the pages required will redirect to login.
- There are 2 resources covered by the ACL permission in the project, they are (1) Page to manage the contacts, (2) Page to manage the users.
- There are 2 roles, they are (1) member and (2) admin, that can be signed to the users.
- The role (1) member can use only the page of Contacts List, the role (2) admin can use all the pages that member has access and he also can use the page to manage users.
  

## Default User information to login
- Email: admin@test.test
- Password: password
  
