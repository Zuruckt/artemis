UID := $(shell id -u)

GID := $(shell id -g)

serve:

	- docker compose up -d

shell:

	- docker exec -it artemis-php /bin/sh
