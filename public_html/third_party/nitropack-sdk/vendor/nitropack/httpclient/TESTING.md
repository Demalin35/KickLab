# NitroPack HttpClient

## Performance testing

1. Install dev dependencies

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --quiet
./composer.phar install -no
```

2. Generate sidecar files, so file encoding doesn't happen on-the-fly and skew results:

The brotli and gzip binaries are a requirement for this step.

```
(cd tests/Performance && ./create_data.php https://www.google.com)
```

2. Spawn a test caddy web server, serving the generated files

```
(cd tests/Performance && ./spawn.sh)
```

3. Execute the performance test

Use environment with the proper php extensions available. Here's an example with a docker container:

```
docker run -it --rm -v $(pwd):/var/www/html -w /var/www/html php:8.3-alpine sh
apk add autoconf g++ make git openssh-client
pecl install -f -o brotli && docker-php-ext-enable brotli
docker-php-ext-install pcntl
```

Make sure to execute the test multiple times to get a more accurate result.

```
for i in {1..3}; do ./bin/console nitropack:http-client:performance-test --url host.docker.internal:3000/www.google.com.html --expected-file /var/www/html/tests/Performance/data/www.google.com.html 1000 10 gzip; [ $i -lt 3 ] && sleep 10; done
```

4. Results

Here's how the output looks like:

```
# cd tests/Performance

# ./create_data.php https://www.google.com/
File created: /var/www/html/tests/Performance/data/www.google.com.html

# ./bin/console nitropack:http-client:performance-test --url host.docker.internal:3000/www.google.com.html --expected-file /var/www/html/tests/Performance/data/www.google.com.html 1000 10 br
Issuing 1000 requests using br encoding with concurrency of 10..
 1000/1000 [============================] 100%

Finished in 1.28 seconds
```