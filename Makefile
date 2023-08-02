#!/usr/bin/make -f
.DEFAULT_GOAL := help
.PHONY: help

COMMAND_COLOR  := \033[0;1;32m
TITLE_COLOR := \033[0;1;33m
NO_COLOR := \033[0m

help: ## List all command name

	@printf "${TITLE_COLOR}Usage:${NO_COLOR}\n";\
	printf "  ${COMMAND_COLOR}make command${NO_COLOR}\n\n"\
	\
	printf "${TITLE_COLOR}Example:${NO_COLOR}\n";\
	printf "  ${COMMAND_COLOR}make help${NO_COLOR}\n\n";\
	\
	printf "${TITLE_COLOR}Available commands:${NO_COLOR}\n";\
	\
	grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' ${MAKEFILE_LIST} \
	| sed -n "s/^\(.*\): \(.*\)##\(.*\)/  $(shell printf "${COMMAND_COLOR}")\1$(shell printf "${NO_COLOR}")@\3/p" \
	| column -t -s'@';

on-app-latest: #sync-pre-commit## On app without elastic search, always build latest app image (dev build)
	uid=`id -u` gid=`id -g` docker-compose up --build --detach --remove-orphans

on-app: #sync-pre-commit ## On app without elastic search
	uid=`id -u` gid=`id -g` docker-compose up --detach --remove-orphans

	@branch_or_tag=$$(git symbolic-ref -q --short HEAD || git describe --tags --exact-match);\
	sha_id=$$(git rev-parse --short HEAD);\
	echo "$$branch_or_tag - $$sha_id" > VERSION

on-app-prod: ## On app in production mode
	@echo "List coverage TAG image exits on your computer:"
	@docker images --format "- {{.Tag}}" coverage-app
	@read -p "Run coverage app with docker image TAG [latest]: " TAG; \
	set -eux; \
	docker-compose up -d maybe_db; \
	TAG=$${TAG:=latest} docker-compose \
		-f docker-compose.yml \
		-f docker-compose.prod.yml \
		up -d;

off-app: ## Off app without elastic search
	uid=`id -u` gid=`id -g` docker-compose \
	-f docker-compose.yml \
	-f docker-compose.override.yml \
	down

build-prod: ## Docker build coverage production image
	@printf "To run in non-interactive mode please run command bellow:\n\t";\
	printf "${TITLE_COLOR}TAG=coverage_tag AWS_ACCOUNT_ID=your_aws_account make build-prod-fargate${NO_COLOR}\n";\
	\
	if [ -z "$$TAG" ]; then\
		read -p "coverage docker image TAG [`date +'%Y.%m.%d'`] TAG=" TAG;\
	fi;\
	\
	if [ -z "$$AWS_ACCOUNT_ID" ]; then\
		read -p "AWS account id [526165498944] AWS_ACCOUNT_ID=" AWS_ACCOUNT_ID;\
	fi;\
	\
	AWS_ACCOUNT_ID=$${AWS_ACCOUNT_ID:=526165498944};\
	TAG=$${TAG:=`date +'%Y.%m.%d'`};\
	printf "${TITLE_COLOR}Building coverage with TAG='$$TAG' and push to aws account AWS_ACCOUNT_ID='$$AWS_ACCOUNT_ID'${NO_COLOR} ...\n";\
	\
	TAG=$${TAG} docker-compose -f docker-compose.yml build\
		--build-arg GIT_BRANCH_NAME=`git symbolic-ref -q --short HEAD || git describe --tags --exact-match`\
		--build-arg GIT_COMMIT_HASH=`git rev-parse --short HEAD`;\
	\
	docker tag coverage-public:$${TAG} $${AWS_ACCOUNT_ID}.dkr.ecr.ap-northeast-1.amazonaws.com/coverage-public:$${TAG};\
	docker tag coverage-app:$${TAG} $${AWS_ACCOUNT_ID}.dkr.ecr.ap-northeast-1.amazonaws.com/coverage-app:$${TAG};\

docker-login: ## Docker login with aws credential (use to push docker image)
	@if [ -z "$$AWS_ACCOUNT_ID" -o -z "$$AWS_ACCESS_KEY_ID" -o -z "$$AWS_SECRET_ACCESS_KEY" ]; then\
		printf "To login to aws ecs please run comman bellow\n";\
		printf "${TITLE_COLOR}AWS_ACCOUNT_ID=x AWS_ACCESS_KEY_ID=y AWS_SECRET_ACCESS_KEY=z make docker-login${NO_COLOR}\n";\
	else\
		AWS_ACCOUNT_ID=$$AWS_ACCOUNT_ID AWS_SECRET_ACCESS_KEY=$$AWS_SECRET_ACCESS_KEY aws ecr get-login-password --region ap-northeast-1 | docker login --username AWS --password-stdin $${AWS_ACCOUNT_ID}.dkr.ecr.ap-northeast-1.amazonaws.com;\
	fi

docker-push: ## Docker push faragte image. Make sure you run docker-login first
	@if [ -z "$$AWS_ACCOUNT_ID" -o -z "$$TAG" ]; then\
		printf "To push coverage fargate image please run:\n";\
		printf "${TITLE_COLOR}AWS_ACCOUNT_ID=x TAG=y make docker-push${NO_COLOR}\n";\
	else\
		docker push $${AWS_ACCOUNT_ID}.dkr.ecr.ap-northeast-1.amazonaws.com/coverage-public:$${TAG};\
		docker push $${AWS_ACCOUNT_ID}.dkr.ecr.ap-northeast-1.amazonaws.com/coverage-app:$${TAG};\
	fi

destroy-app: clean ## Cleanup tenant dir, run this on your personal computer only
	uid=`id -u` gid=`id -g` docker-compose \
	-f docker-compose.yml \
	-f docker-compose.override.yml \
	down -v

composer-install-test-libraries: ## Composer install on tests directory
	cd tests && composer install

test: on-app## Run phpunit test app integration with database
	docker exec -t maybe_app tests/phpunit --no-coverage --testdox -c tests/phpunit.xml

test-library: on-app ## Run phpunit test library only - testdox
	docker exec -t maybe_app tests/phpunit --no-coverage --testdox -c tests/phpunit.lib.xml

test-maybe: on-app ## Measure test coverage
	docker exec -t -e XDEBUG_MODE=coverage maybe_app tests/phpunit -d memory_limit=-1 -c tests/phpunit.xml

clean-test-maybe: ## Clean test coverage results
	rm -rf tests/maybe

ssh-app: ## SSH into coverage app container
	docker exec -it maybe_app sh

ssh-app-as-root: ## SSH into coverage app container as root user
	docker exec -it --user 0 maybe_app sh

show-log-app: ## Show log coverage app container
	docker logs -f maybe_app

show-log-app-nginx: ## Show log coverage nginx container
	docker logs -f maybe_public

show-log-db: ## Show log mysql server container
	docker logs -f maybe_db

# show-log-saml: ## Show log saml server - keycloak container
# 	docker logs -f keycloak
npm-run: ## Run NPM run in APP container
	docker exec -it --user 0 maybe_app npm run dev

check-eslint: ## Run check eslint in source
	docker exec -t --user 0 maybe_app npm run eslint


composer-install: ## Run Composer install in APP container
	docker exec -t maybe_app composer install

npm-install: ## Run NPM install in APP container
	rm -rf node_modules && docker exec -t --user 0 maybe_app npm install

up-db: ## Migrate database for all tenant
	docker exec -t maybe_app php artisan migrate

# clean: ## Remove all tenant dir, run this on your personal computer only
# 	rm -rfv application/configs/*.application.ini;\
# 	rm -rfv ^data/multitenancy/.gitkeep data/multitenancy/*;\
# 	rm -rfv ^public/multitenancy/.gitkeep public/multitenancy/*;\
# 	rm -rfv ^application/modules/issue/views/scripts/issuever5/multitenancy/.gitignore application/modules/issue/views/scripts/issuever5/multitenancy/*;\
# 	rm -rfv ^application/modules/issue/views/scripts/searchbasic/multitenancy/.gitignore application/modules/issue/views/scripts/searchbasic/multitenancy/*

# list-tenant: ## List all tenant name
# 	@cd data/multitenancy && ls -1d * 2>/dev/null || printf '${TITLE_COLOR}Not found any tenant.\nPlease install coverage app first.${NO_COLOR}\n'

#sync-pre-commit:
#	cp tools/pre-commit .git/hooks/pre-commit

# off-2step-login: ## Turn off 2 step login for docker develop env
# 	@for application_ini in ./application/configs/*.application.ini; do\
# 		[ ! -e $$application_ini ] && printf "${TITLE_COLOR}Firstly please install app /install.php after that run this task again.${NO_COLOR}\n" && continue ;\
# 		dbname=$$(awk -F "=" "/resources.db.params.dbname/ {print \$$2}" $$application_ini  | tr -d " \"");\
# 		printf "Turn off 2step login for db ${COMMAND_COLOR}$$dbname${NO_COLOR} founded in ${COMMAND_COLOR}$$application_ini${NO_COLOR}\n";\
# 		docker-compose exec maybe_db mysql -pcdmllove $$dbname -e "update crm_member set member_used_2auth=0 where member_id=1;";\
# 	done
#
# vagrant-off-2step-login: ## Turn off 2 step login for vagrant develop env
# 	@for application_ini in ./application/configs/*.application.ini; do\
# 		[ ! -e $$application_ini ] && printf "${TITLE_COLOR}Firstly please install app /install.php after that run this task again${NO_COLOR}\n" && continue ;\
# 		dbname=$$(awk -F "=" "/resources.db.params.dbname/ {print \$$2}" $$application_ini  | tr -d " \"");\
# 		printf "Turn off 2step login for db ${COMMAND_COLOR}$$dbname${NO_COLOR} founded in ${COMMAND_COLOR}$$application_ini${NO_COLOR}\n";\
# 		mysql -pcdmllove $$dbname -e "update crm_member set member_used_2auth=0 where member_id=1;";\
# 	done