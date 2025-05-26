#!/bin/bash

SOURCE_PATH="/app"
USER_NAME=App-User
USER_GROUP=App-User
USER_HOME="/home/$USER_NAME"
BUILD="${BUILD:-true}"
ENV="${ENV:-dev}"
MIGRATE="${MIGRATE:-true}"
MIGRATE_FRESH="${MIGRATE_FRESH:-false}"
KILL="${KILL:-true}"
USER_UID="${USER_UID:-0}"
USER_GID="${USER_GID:-0}"

pull_composer() {
  echo "pulling composer"
  EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

  if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]
  then
    >&2 echo 'ERROR: Invalid installer checksum'
    rm composer-setup.php
    exit 1
  fi

  php composer-setup.php --quiet
  RESULT=$?
  rm composer-setup.php
  echo "done";
  return $RESULT
}

handle_user() {
  if [[ -z $(getent group "$USER_GID") ]]; then
    groupadd "$USER_GROUP" -g "$USER_GID";
  fi
  ACTUAL_GROUP=$(getent group "$USER_GID" | cut -d: -f1)
  if [[ -z $(getent passwd "$USER_UID") ]]; then
    mkdir -p "$USER_HOME"
    useradd App-User -u "$USER_UID" -g "$USER_GID" -s/bin/bash -d "$USER_HOME"
    chown -R $(id -un "$USER_UID"):"$ACTUAL_GROUP" "$USER_HOME"
  fi
  chown -R $(id -un "$USER_UID"):"$ACTUAL_GROUP" "$SOURCE_PATH"
  su - $(id -un "$USER_UID")
}

main() {
  if [ -z "$(ls -A $SOURCE_PATH)" ]; then
    echo "You may have forgotten to mount your app to $SOURCE_PATH in the container."
    exit 128;
  fi
  git config --global --add safe.directory "$SOURCE_PATH"

  handle_user || exit 256;

  cd "$SOURCE_PATH"
  if [[ "$BUILD" == "true" ]]; then
    echo "Pulling the dependencies"
    cd "$HOME"
    pull_composer || exit 64
    cd "$SOURCE_PATH"
    if [[ "$ENV" == "dev" ]]; then
        php ~/composer.phar install
    else
        php ~/composer.phar install --no-dev
    fi;
    rm ~/composer.phar
    echo "done"
  fi
  if [[ "$MIGRATE" == "true" ]]; then
    if [[ "$MIGRATE_FRESH" == "true" ]]; then
      echo yes | php yii migrate/fresh || ( echo "MIGRATION FAILED!" && exit 32 )
    else
      echo yes | php yii migrate || ( echo "MIGRATION FAILED!" && exit 32 )
    fi
  fi
  if [[ "$KILL" == "true" ]]; then
    echo "ending before serving"
    exit 0
  fi
  exec php yii serve 0.0.0.0
} 

main "$@"