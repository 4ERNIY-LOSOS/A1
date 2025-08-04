#!/bin/sh

cmd="/usr/local/bin/composer"

for i in $@
  do
    cmd="$cmd $i"
done

$cmd

sh /root/www-data.sh
