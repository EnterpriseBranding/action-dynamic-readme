#!/bin/sh
set -e

source /gh-toolkit/shell.sh

gitconfig "Github Action Dynamic Template"

gh_validate_input "FILES" "FILES List is required"

mkdir -p /dynamic-readme-tmp/repos/

if [ -z "$GITHUB_TOKEN" ]; then
  gh_log_error "🚩 Set the GITHUB_TOKEN env variable"
fi

if [ -z "$REPOSITORY_TOPICS" ]; then
  gh_log_warning "Repository Meta Information Not Found"
  gh_log "ℹ︎ Using https://github.com/varunsridharan/action-repository-meta Action To Fetch Meta Information"
  cd /
  git clone https://github.com/varunsridharan/action-repository-meta
  cp -r action-repository-meta/app /gh-repo-meta/
  sh action-repository-meta/entrypoint.sh
  echo " "
fi

RAW_FILES=$(gh_input "FILES")

FILES=($RAW_FILES)

GIT_URL="https://x-access-token:${GH_TOKEN}@github.com/${GITHUB_REPOSITORY}.git"

for FILE in "${FILES[@]}"; do
  SRC_FILE=${FILES[0]}
  if [ ${FILES[1]+yes} ]; then
    DEST_FILE="${FILES[1]}"
  else
    DEST_FILE="${SRC_FILE}"
  fi

  DEST_FOLDER_PATH=$(dirname "${GITHUB_WORKSPACE}/${DEST_FILE}")

  if [ ! -d "$DEST_FOLDER_PATH" ]; then
    gh_log "  Creating [$DEST_FOLDER_PATH]"
    mkdir -p $DEST_FOLDER_PATH
  fi

  git add "${GIT_PATH}/${DEST_FILE}" -f

  php /dynamic-readme/app.php "${SRC_FILE}" "${DEST_FILE}"

  if [ "$(git status --porcelain)" != "" ]; then
    git commit -m "💬 - File Rebuilt | Github Action Runner : ${GITHUB_RUN_NUMBER}"
  else
    gh_log "  ✅ No Changes Are Done : ${SRC_FILE}"
  fi
done

git push $GIT_URL
