.PHONY: all app connect

all:
	docker build -t obsmulti_db .

app:
	docker run -p 3306:3306 obsmulti_db

connect:
	mysql -h 127.0.0.1 -u obsmulti_client -p'thepass123' -D obsmulti
