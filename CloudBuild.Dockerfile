# For GCP / Cloud Build
#FROM devopsfnl/image:php-8.1-laravel-newman-xdebug3
FROM jdecode/php:jre-jdk-py-py3-phpize

ARG PORT
ENV PORT=${PORT}

COPY . /var/www/html

RUN composer install -n --prefer-dist
RUN composer run ci

RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

RUN chmod +x /var/www/html/deployer.sh

ENTRYPOINT ["/var/www/html/deployer.sh"]
