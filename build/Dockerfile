FROM alpine:edge
MAINTAINER Mike Kornelson <darkadept@durbn.net>

RUN apk --no-cache add php7 php7-fpm php7-mysqli php7-json php7-openssl php7-curl \
    php7-zlib php7-xml php7-phar php7-intl php7-dom php7-xmlreader php7-ctype \
    php7-mbstring php7-gd php7-session php7-gmp php7-imap php7-mcrypt php7-tidy php7-xmlrpc \
    nginx supervisor curl nano

# Configure nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY fpm-pool.conf /etc/php7/php-fpm.d/zzz_custom.conf
COPY php.ini /etc/php7/conf.d/zzz_custom.ini

# Configure supervisord
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ARG ZIP=/tmp/zip
ARG UNZIP=/tmp/unzip
ARG HTML=/var/www/html
ARG PLUGINS=$HTML/lib/plugins
ARG TPL=$HTML/lib/tpl

RUN mkdir -p $ZIP && \
    mkdir -p $UNZIP && \
    mkdir -p $HTML

WORKDIR $ZIP

RUN curl -o "$ZIP/dokuwiki.tgz" -L "https://download.dokuwiki.org/src/dokuwiki/dokuwiki-stable.tgz" && \
		tar -zxf dokuwiki.tgz -C $UNZIP && \
		mv $UNZIP/dokuwiki-2017-02-19e/* $HTML/ && \
		mv $UNZIP/dokuwiki-2017-02-19e/.h* $HTML/ && \
		rm -rf $UNZIP/dokuwiki-2017-02-19e

RUN curl -O -L "http://dev.xif.fr:7979/catlist/catlist-2017-09-28.zip" && \
		mkdir -p $UNZIP/catlist && \
    unzip catlist-2017-09-28.zip -d $UNZIP/catlist && \
    mv $UNZIP/* $PLUGINS/catlist

RUN curl -o "$ZIP/commentsyntax.zip" -L "https://github.com/ssahara/dw-plugin-commentsyntax/zipball/master" && \
    unzip $ZIP/commentsyntax.zip -d $UNZIP && \
    mv $UNZIP/* $PLUGINS/commentsyntax

RUN curl -o "$ZIP/csv.zip" -L "https://github.com/cosmocode/csv/zipball/master" && \
    unzip $ZIP/csv.zip -d $UNZIP && \
    mv $UNZIP/* $PLUGINS/csv

RUN curl -o "$ZIP/fontawesome.zip" -L "https://github.com/mmedvede/dokuwiki-plugin-fontawesome/archive/master.zip" && \
    unzip $ZIP/fontawesome.zip -d $UNZIP && \
    mv $UNZIP/* $PLUGINS/fontawesome

RUN curl -o "$ZIP/hidden.zip" -L "https://github.com/gturri/hidden/zipball/master" && \
    unzip $ZIP/hidden.zip -d $UNZIP && \
    mv $UNZIP/* $PLUGINS/hidden

RUN curl -o "$ZIP/imgpaste.zip" -L "https://github.com/cosmocode/dokuwiki-plugin-imgpaste/zipball/master" && \
    unzip $ZIP/imgpaste.zip -d $UNZIP && \
    mv $UNZIP/* $PLUGINS/imgpaste

RUN curl -o "$ZIP/include.zip" -L "https://github.com/dokufreaks/plugin-include/archive/master.zip" && \
    unzip $ZIP/include.zip -d $UNZIP && \
    mv $UNZIP/* $PLUGINS/include

RUN curl -o "$ZIP/mediarename.zip" -L "https://github.com/cosmocode/dokuwiki-plugin-mediarename/archive/master.zip" && \
    unzip $ZIP/mediarename.zip -d $UNZIP && \
    mv $UNZIP/* $PLUGINS/mediarename

RUN curl -o "$ZIP/orphanmedia.zip" -L "https://github.com/Taggic/orphanmedia/zipball/master" && \
    unzip $ZIP/orphanmedia.zip -d $UNZIP && \
    mv $UNZIP/* $PLUGINS/orphanmedia

RUN curl -o "$ZIP/orphanswanted.zip" -L "https://github.com/lupo49/dokuwiki-plugin-orphanswanted/zipball/master" && \
    unzip $ZIP/orphanswanted.zip -d $UNZIP && \
    mv $UNZIP/* $PLUGINS/orphanswanted

RUN curl -o "$ZIP/upload.zip" -L "https://github.com/lupo49/dokuwiki-plugin-upload/archive/master.zip" && \
    unzip $ZIP/upload.zip -d $UNZIP && \
    mv $UNZIP/* $PLUGINS/upload

RUN curl -o "$ZIP/wrap.zip" -L "https://github.com/selfthinker/dokuwiki_plugin_wrap/archive/master.zip" && \
    unzip $ZIP/wrap.zip -d $UNZIP && \
    mv $UNZIP/* $PLUGINS/wrap

RUN rm -rf $ZIP && \
		rm -rf $UNZIP

# ADD src/plugins/ $PLUGINS/
# ADD src/tpl/dokui $TPL/dokui/

RUN mkdir -p /original && \
		mv $HTML/conf /original && \
		mv $HTML/data /original && \
		mv $HTML/lib/plugins /original && \
		mv $HTML/lib/tpl /original

RUN chown -R nginx:nginx $HTML && \
    chmod -R 755 $HTML/lib
#		chmod -R a+rw $PLUGINS && \		
#		chmod -R a+rw $TPL

WORKDIR /
COPY entrypoint.sh /
RUN chmod a+x /entrypoint.sh
EXPOSE 80
CMD ["/entrypoint.sh"]
