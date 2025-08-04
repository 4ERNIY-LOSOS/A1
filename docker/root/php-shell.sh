#!/bin/sh
cmd="php"

for i in $@
  do
    cmd="$cmd $i"
done

$cmd

case "$1" in
  console | /hleb/console | ./console )
    sh /root/www-data.sh
    ;;
  * )
    ;;
esac

