#!/bin/sh
set -e

source /gh-toolkit/shell.sh

gh_log_group_start 'Testing Group'
gh_log_warning 'This is a simple warning data'
gh_log_group_end



gitconfig "Hello World" "example@example.com"

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

php /gh-toolkit/index.php