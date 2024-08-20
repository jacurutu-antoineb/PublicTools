VER=1.0

.PHONY: 

all:
	docker build -t etl:v$(VER) .
	cd ..; docker-compose up -d --no-deps --force-recreate etl; cd etl;

test:
	bash -c 'MYSQL_HOST=127.0.0.1 MYSQL_DATABASE=obsmulti MYSQL_USER=obsmulti_client MYSQL_PASSWORD=thepass123 python3 app.py'

