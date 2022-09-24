# For GCP / Cloud Build
#FROM devopsfnl/image:php-8.1-laravel-newman-xdebug3
#FROM jdecode/php:jre-jdk-py-py3-phpize
FROM asia-southeast1-docker.pkg.dev/online-compiler-363217/hybrid/java-python3-php:latest

ARG PORT
ENV PORT=${PORT}

COPY . /var/www/html

RUN composer install -n --prefer-dist

RUN mkdir public/build && chmod -R 0777 public/build
RUN npm install
RUN npm run build

RUN chmod -R 0777 storage bootstrap public/build



RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

RUN chmod +x /var/www/html/deployer.sh

ENTRYPOINT ["/var/www/html/deployer.sh"]
