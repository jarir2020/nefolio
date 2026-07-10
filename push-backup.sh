#!/bin/bash

# Ensure script exits if any command fails
set -e

# Check if we are inside a git repository
if ! git rev-parse --is-inside-work-tree > /dev/null 2>&1; then
    echo "Error: Not a git repository."
    exit 1
fi

# Get current branch
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)

# Get the commit message
COMMIT_MSG="$1"

if [ -z "$COMMIT_MSG" ]; then
    read -p "Enter commit message (or press Enter for default): " COMMIT_MSG
fi

# If still empty, use a default
if [ -z "$COMMIT_MSG" ]; then
    COMMIT_MSG="deploy: update files"
fi

echo "Staging all changes..."
git add -A

# Check if there are changes to commit
if git diff-index --quiet HEAD --; then
    echo "No changes to commit. Working tree is clean."
    exit 0
fi

echo "Committing changes with message: '$COMMIT_MSG'..."
git commit -m "$COMMIT_MSG"

echo "Pushing changes to remote branch '$CURRENT_BRANCH'..."
git push origin "$CURRENT_BRANCH"

echo "Push complete. Changes pushed to GitHub for backup."
