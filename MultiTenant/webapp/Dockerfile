FROM trafex/php-nginx

USER root

RUN apk add mysql-client
RUN apk add mariadb

USER nobody
COPY app/web/ /var/www/html


