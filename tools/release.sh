#!/bin/bash

# ------------------------------
# Paths, urls and other settings
# ------------------------------
SOURCEDIR="/srv/http/DraiWiki"
RELEASEDIR="/srv/http/releases"

OVERWRITE_EXISTING=0

echo ":: DraiWiki release utility"

if [ -z "$1" ]; then
    echo "[error] No version number specified."
    exit
fi

echo "Preparing to release version $1"

# --------------------------------------------------------------
# Create the release directory, but only if it doesn't exist yet
# --------------------------------------------------------------
sudo mkdir -p -m 770 "$RELEASEDIR"
sudo chown -R "$USER" "$RELEASEDIR"

DESTINATION="$RELEASEDIR/$1"

# ------------------------------------------------
# Overwrite the existing directory if there is one
# ------------------------------------------------
if [ -d "$RELEASEDIR/$1" ]; then
    echo "Directory already exists. Overwrite? [Y/n]"

    read canOverwrite

    if [ "$canOverwrite" == "y" ] || [ "$canOverwrite" == "Y" ]; then
        OVERWRITE_EXISTING=1
    fi
fi

if [ "$OVERWRITE_EXISTING" == 1 ]; then
    sudo rm -rf "$DESTINATION"
fi

# ---------------------------------------
# Create a new directory for this release
# ---------------------------------------
sudo mkdir -m 770 "$DESTINATION"
sudo chown -R "$USER" "$DESTINATION"

echo "$DESTINATION/${1// /_}.tar.gz"

# -------------------
# Create the packages
# -------------------
tar --exclude="node_modules" --exclude="vendor" --exclude=".git" --exclude=".idea" --exclude="tools" -czvf "$DESTINATION/${1// /_}_minimal.tar.gz" -C "$SOURCEDIR" .
tar --exclude="node_modules" --exclude="vendor" --exclude=".idea" -czvf "$DESTINATION/${1// /_}_developer.tar.gz" -C "$SOURCEDIR" .

echo "Release process completed. Good work!"