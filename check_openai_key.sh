#!/usr/bin/env bash
set -euo pipefail

KEY_FILE="keys.txt"
if [ ! -f "$KEY_FILE" ]; then
  echo "Missing $KEY_FILE."
  exit 1
fi

found_any=0
valid_count=0
invalid_count=0
error_count=0

while IFS= read -r api_key || [ -n "$api_key" ]; do
  api_key="$(printf '%s' "$api_key" | tr -d '\r\n' | xargs)"

  if [ -z "$api_key" ] || [[ "$api_key" == \#* ]]; then
    continue
  fi

  found_any=1
  tmp_body="$(mktemp)"
  code="$(
    curl -sS -o "$tmp_body" -w '%{http_code}' \
      https://api.openai.com/v1/models \
      -H "Authorization: Bearer ${api_key}" \
      -H "Content-Type: application/json"
  )"

  if [ "$code" = "200" ]; then
    echo "VALID"
    valid_count=$((valid_count + 1))
  elif [ "$code" = "401" ]; then
    echo "INVALID"
    invalid_count=$((invalid_count + 1))
  else
    echo "ERROR ($code)"
    cat "$tmp_body"
    error_count=$((error_count + 1))
  fi

  rm -f "$tmp_body"
done < "$KEY_FILE"

if [ "$found_any" -eq 0 ]; then
  echo "No API keys found in $KEY_FILE."
  exit 1
fi

echo "Summary: valid=$valid_count invalid=$invalid_count error=$error_count"

if [ "$invalid_count" -gt 0 ] || [ "$error_count" -gt 0 ]; then
  exit 1
fi

exit 0
