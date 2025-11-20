.PHONY: phpstan rector phpstan-fix

phpstan:
	docker compose exec backend vendor/bin/phpstan analyse --memory-limit=256M

rector:
	docker compose exec backend vendor/bin/rector process --dry-run

rector-fix:
	docker compose exec backend vendor/bin/rector process --no-progress-bar

