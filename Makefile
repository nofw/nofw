BUILD_IMAGES=app

# Setup environment
setup: build
	mkdir -p var/docker
	RAILS_ENV=production docker-compose run --rm errbit bundle exec rake db:seed

# Build images
build:
ifeq ($(FORCE), true)
	@docker-compose build --force-rm $(BUILD_IMAGES)
else
	@docker-compose build $(BUILD_IMAGES)
endif

# Start the environment
start:
	@docker-compose up -d

# Stop the environment
stop:
ifeq ($(FORCE), true)
	@docker-compose kill
else
	@docker-compose stop
endif

# Clean environment
clean: stop
	@rm -rf vendor/ var/cache/* var/docker
	@docker-compose rm --force

# Run test suite
test:
	@composer test

# Install dependencies
install:
	@docker-compose run --rm composer install --ignore-platform-reqs

.PHONY: setup build start stop clean test install
