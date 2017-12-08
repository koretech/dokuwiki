#!/bin/sh

WEB_PATH=/var/www/html
DATA_PATH=/data

if [ ! -d "${DATA_PATH}/conf" ]; then
	mkdir ${DATA_PATH}/conf
	cp -a /original/conf/* ${DATA_PATH}/conf
	chown -R nginx:nginx "${DATA_PATH}/conf"
fi

if [ ! -d "${DATA_PATH}/data" ]; then
	mkdir ${DATA_PATH}/data
	cp -a /original/data/* ${DATA_PATH}/data
	chown -R nginx:nginx "${DATA_PATH}/data"
fi

if [ ! -f "${WEB_PATH}/conf" ]; then
	ln -s ${DATA_PATH}/conf ${WEB_PATH}/
fi

if [ ! -f "${WEB_PATH}/data" ]; then
	ln -s ${DATA_PATH}/data ${WEB_PATH}/
fi

chown -R nginx:nginx ${DATA_PATH}
chmod -R a+rw ${DATA_PATH}/data
chmod -R 644 ${DATA_PATH}/conf/*

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
