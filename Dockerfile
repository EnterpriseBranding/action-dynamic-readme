#FROM php:cli-alpine
FROM varunsridharan/php-github-actions-toolkit:0.3
ENV INSTALL_GIT="Yeeeeeeeea GO HEAD"

#RUN apk add git

COPY entrypoint.sh /entrypoint.sh

#COPY src/ /dynamic-readme/

RUN chmod +x /entrypoint.sh

#RUN chmod -R 777 /dynamic-readme/

ENTRYPOINT ["/entrypoint.sh"]