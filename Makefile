UID := $(shell id -u)

GID := $(shell id -g)

serve:

	- docker compose up -d

bash:

	- docker exec -it --user $(UID):$(GID) artemis-php bash
