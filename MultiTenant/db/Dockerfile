FROM mariadb:latest

ENV MYSQL_ROOT_PASSWORD=mypass

COPY ./schema.sql /docker-entrypoint-initdb.d/

EXPOSE 3306
