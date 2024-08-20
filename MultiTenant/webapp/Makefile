VER=1.0

.PHONY: all app compose

all:
	docker build -t obsmulti:v$(VER) .
	cd ..; docker-compose up -d --no-deps --force-recreate frontend; cd webapp;


app:
	docker run -p 5000:8080 obsmulti:v$(VER)

compose:
	docker build -t obsmulti:v$(VER) .
	cd ..; docker-compose down; docker-compose up;
