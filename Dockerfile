#FROM php:cli-alpine
FROM varunsridharan/php-github-actions-toolkit:latest

#RUN apk add git
RUN /github-toolkit/scripts/git.sh

COPY entrypoint.sh /entrypoint.sh

#COPY src/ /dynamic-readme/

RUN chmod +x /entrypoint.sh

#RUN chmod -R 777 /dynamic-readme/

ENTRYPOINT ["/entrypoint.sh"]