VER=1.4

all:
	docker build -t obsapi:v$(VER) .

app:
	docker run -p 5000:80 obsapi:v$(VER)

testapi:
	cd test && ./testapp_api.sh

