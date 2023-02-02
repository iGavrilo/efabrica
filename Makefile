all:
	@awk 'BEGIN {FS = ":.*##"; printf "Usage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"}'
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

# QA
phpstan: ## Analyse code with PHPStan
	vendor/bin/phpstan analyse --level 8 --configuration phpstan.neon app$(ARGS)

test: ## Nette tester
	vendor/bin/tester . --colors 1 $(ARGS)

phpstan-github: ## Analyse code with PHPStan
	vendor/bin/phpstan analyse --level 8 --configuration phpstan.neon app --error-format=github $(ARGS)
