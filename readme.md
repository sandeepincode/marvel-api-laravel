# Marvel API with Laravel
The application validates all inputs and sends requests to the appropriate Marvel API endpoint. The endpoint response is dealt with appropriately and a CSV is downloaded, and formatted.
<br/>

# Example use case
If you enter "hulk" and select comics, you get the top 40 comics ordered by the on sale date.
<br/>
An example output can be found in "Marvel-API.csv".



# Files To Note:
1. Route: app/Http/routes.php
2. Controller: app/Http/Controllers/apiController.php
3. View: resources/views/main.blade.php
4. Test: tests/RouteTest.php

# How to run
1. 'composer install' or 'php artisan install'
2. 'composer serve' or 'php artisan serve'
3. The application will be running on <a href>http://localhost:8000/

#Testing
Run this command after install: ./vendor/bin/phpunit
<br/>

#Preview

![Alt text](/example.png?raw=true "Title")
