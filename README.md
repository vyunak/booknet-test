> booknet-test
- Link https://github.com/Litnet-Ltd/php-test
- I didn't put a lot of effort into some of the features, because I think I spent quite a lot of time on the test.

1) `composer install`
2) `php -S localhost:3000 index.php`
3) `curl --request GET \
   --url 'http://localhost:3000/?productType=book&amount=31&lang=ru&countryCode=ru&userOs=ios'`