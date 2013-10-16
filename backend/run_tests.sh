#!/bin/sh
app/console cache:clear --no-warmup --env=test
app/console cache:warmup --env=test
bin/phpunit -c app/ $@
