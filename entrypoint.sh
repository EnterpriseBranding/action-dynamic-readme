#!/bin/sh
set -e
echo " "

if [ -z "$GITHUB_TOKEN" ]; then
  echo "🚩 Set the GITHUB_TOKEN env variable"
fi

if [ -z "$REPOSITORY_TOPICS" ]; then
  echo "⚠️ Repository Meta Information Not Found"
  echo "ℹ︎ Using https://github.com/varunsridharan/action-repository-meta Action To Fetch Meta Information"
  cd /
  git clone https://github.com/varunsridharan/action-repository-meta
  cp -r action-repository-meta/app /gh-repo-meta/
  sh action-repository-meta/entrypoint.sh
  echo " "
fi

echo "Repository Topics : ${REPOSITORY_TOPICS}"