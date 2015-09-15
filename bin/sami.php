#!/usr/bin/env sh
SRC_DIR="`pwd`"
cd "`dirname "$0"`"
cd "../vendor/sami/sami"
BIN_TARGET="`pwd`/sami.php"
cd "$SRC_DIR"
"$BIN_TARGET" "$@"
