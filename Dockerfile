FROM varunsridharan/actions-alpine:latest
FROM varunsridharan/php-github-actions-toolkit:latest

COPY entrypoint.sh /entrypoint.sh

COPY src/ /dynamic-readme/

RUN chmod +x /entrypoint.sh

RUN chmod -R 777 /dynamic-readme/

ENTRYPOINT ["/entrypoint.sh"]