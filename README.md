TRUSTBACK.ME THEME
==================

This is a Bootstrap 4 based child theme for Genesis, customized to be used in TrustBack:me WordPress sites.

How does this work
------------------

This theme is based on Bootstrap 4 and is completely customizable.

You have the ability to fully configure Bootstrap and then compile your theme to a zipped file that can be served through an update server so it can be downloaded by any Wordpress installation that uses it.

### STEP 1: Download all the dependencies

   yarn install

### STEP 2: Create a `style.css` file from `style.dist.css`

The `style.dist.css` file is the file where all the Bootstrap `.scss` stylesheets are included.

By default they are all commented, so you have to copy the file, rename it to `style.css` and uncomment all the features of Bootstrap you like to have in your template.

### STEP 3: Create a `config.php` file from `config.dist.php`

Same thing: copy and rename, then change values accordingly to your project needs.

Development commands
--------------------

```console
./node_modules/.bin/eslint "./src/**/*.js" --fix &&
vendor/bin/phpcs
```
