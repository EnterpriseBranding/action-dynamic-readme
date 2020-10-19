#FROM php:cli-alpine
FROM varunsridharan/php-github-actions-toolkit:0.3

#RUN apk add git
RUN /toolkit-scripts/git.sh

COPY entrypoint.sh /entrypoint.sh

#COPY src/ /dynamic-readme/

RUN chmod +x /entrypoint.sh

#RUN chmod -R 777 /dynamic-readme/

ENTRYPOINT ["/entrypoint.sh"]