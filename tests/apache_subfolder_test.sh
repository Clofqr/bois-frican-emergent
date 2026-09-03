#!/bin/bash
# Iteration 6 - Validation des 3 fixes (sous-dossier basePath, sécurité location-independent, casse Video)
ROOT="http://127.0.0.1:8080"
SUB="http://127.0.0.1:8081/bois-frican"
PASS=0; FAIL=0; FAILS=()
chk() { code=$(curl -s -o /tmp/b.out -w "%{http_code}" "$2");
  if [ "$code" == "$1" ]; then echo "PASS [$code] $3"; PASS=$((PASS+1));
  else echo "FAIL [got $code want $1] $3"; FAIL=$((FAIL+1)); FAILS+=("$3 got=$code want=$1"); fi }

echo "=== A. configtest / .htaccess sanity ==="
apachectl configtest 2>&1 | grep -q "Syntax OK" && { echo "PASS Syntax OK"; PASS=$((PASS+1)); } || { echo "FAIL configtest"; FAIL=$((FAIL+1)); FAILS+=("configtest"); }
grep -qi "<Directory" /app/.htaccess && { echo "FAIL <Directory> present in /app/.htaccess"; FAIL=$((FAIL+1)); FAILS+=("Directory in htaccess"); } || { echo "PASS no <Directory> in /app/.htaccess"; PASS=$((PASS+1)); }
grep -q "FilesMatch" /app/images/.htaccess && { echo "PASS images/.htaccess FilesMatch present"; PASS=$((PASS+1)); } || { echo "FAIL images/.htaccess"; FAIL=$((FAIL+1)); FAILS+=("images htaccess"); }

echo "=== B. Pages 200 - ROOT vhost 8080 ==="
for p in "" index.php fbf_apropos.php fbf_contact.php fbf_decouverte.php fbf_infopratique.php fbf_mentionslegales.php accueil/fbf_accueil.php; do
  chk 200 "$ROOT/$p" "ROOT GET /$p"; done

echo "=== C. Pages 200 - SUBFOLDER vhost 8081 ==="
for p in "" index.php fbf_apropos.php fbf_contact.php fbf_decouverte.php fbf_infopratique.php fbf_mentionslegales.php accueil/fbf_accueil.php; do
  chk 200 "$SUB/$p" "SUB GET /bois-frican/$p"; done

echo "=== D. Assets referenced by SUBFOLDER home (resolved) ==="
# extraire href/src relatifs de la home sous-dossier et les tester
curl -s "$SUB/" > /tmp/home_sub.html
python3 - <<'PY' > /tmp/assets_sub.txt
import re
h=open('/tmp/home_sub.html',encoding='utf-8',errors='ignore').read()
urls=set()
for m in re.finditer(r'<link[^>]+rel=["\']stylesheet["\'][^>]*>',h,re.I):
    u=re.search(r'href=["\']([^"\']+)',m.group(0));  urls.add(u.group(1)) if u else None
for m in re.finditer(r'<script[^>]+src=["\']([^"\']+)',h,re.I): urls.add(m.group(1))
for m in re.finditer(r'<img[^>]+src=["\']([^"\']+)',h,re.I): urls.add(m.group(1))
for m in re.finditer(r'<source[^>]+src=["\']([^"\']+)',h,re.I): urls.add(m.group(1))
for m in re.finditer(r'poster=["\']([^"\']+)',h,re.I): urls.add(m.group(1))
for u in sorted(urls):
    if u.startswith(('http','//','data:')): continue
    print(u)
PY
echo "--- assets found: $(wc -l < /tmp/assets_sub.txt)"
while read -r a; do
  # resolution relative a http://127.0.0.1:8081/bois-frican/
  case "$a" in
    /*) full="http://127.0.0.1:8081$a";;
    ./*) full="$SUB/${a#./}";;
    ../*) full="http://127.0.0.1:8081/${a#../}";;
    *) full="$SUB/$a";;
  esac
  chk 200 "$full" "SUB asset $a -> $full"
done < /tmp/assets_sub.txt

echo "=== E. Assets referenced by SUB /accueil/fbf_accueil.php (fallback ../) ==="
curl -s "$SUB/accueil/fbf_accueil.php" > /tmp/home_sub2.html
sed -i 's#/tmp/home_sub.html#/tmp/home_sub2.html#' /dev/null
python3 - <<'PY' > /tmp/assets_sub2.txt
import re
h=open('/tmp/home_sub2.html',encoding='utf-8',errors='ignore').read()
urls=set()
for m in re.finditer(r'<link[^>]+rel=["\']stylesheet["\'][^>]*>',h,re.I):
    u=re.search(r'href=["\']([^"\']+)',m.group(0))
    if u: urls.add(u.group(1))
for pat in [r'<script[^>]+src=["\']([^"\']+)', r'<img[^>]+src=["\']([^"\']+)', r'<source[^>]+src=["\']([^"\']+)', r'poster=["\']([^"\']+)']:
    for m in re.finditer(pat,h,re.I): urls.add(m.group(1))
for u in sorted(urls):
    if u.startswith(('http','//','data:')): continue
    print(u)
PY
echo "--- assets found: $(wc -l < /tmp/assets_sub2.txt)"
while read -r a; do
  case "$a" in
    /*) full="http://127.0.0.1:8081$a";;
    ./*) full="$SUB/accueil/${a#./}";;
    ../*) full="$SUB/${a#../}";;
    *) full="$SUB/accueil/$a";;
  esac
  chk 200 "$full" "SUB-direct asset $a -> $full"
done < /tmp/assets_sub2.txt

echo "=== F. Securite - SUBFOLDER 8081 (doit etre 403) ==="
for f in memory/test_credentials.md memory/PRD.md test_reports/iteration_1.json backend/server.py frontend/package.json admin/index.php admin/reservations.php vendor/autoload.php; do
  chk 403 "$SUB/$f" "SUB blocked /$f"; done

echo "=== G. Securite - ROOT 8080 (regression, doit etre 403) ==="
for f in memory/test_credentials.md memory/PRD.md test_reports/iteration_1.json backend/server.py frontend/package.json admin/index.php admin/reservations.php vendor/autoload.php; do
  chk 403 "$ROOT/$f" "ROOT blocked /$f"; done

echo "=== H. Video casse ==="
for v in images/Video.webm images/Video.mp4; do
  chk 200 "$SUB/$v" "SUB $v"
  echo "   CT: $(curl -s -o /dev/null -w '%{content_type}' "$SUB/$v")"
done
if curl -s "$SUB/fbf_decouverte.php" | grep -q 'images/Video.webm'; then echo "PASS HTML contient images/Video.webm"; PASS=$((PASS+1)); else echo "FAIL HTML sans images/Video.webm"; FAIL=$((FAIL+1)); FAILS+=("Video.webm html"); fi
if curl -s "$SUB/fbf_decouverte.php" | grep -q 'images/video\.webm'; then echo "FAIL HTML contient video.webm minuscule"; FAIL=$((FAIL+1)); FAILS+=("video.webm lowercase"); else echo "PASS pas de video.webm minuscule"; PASS=$((PASS+1)); fi
ls /app/images | grep -iE '^video\.(webm|mp4)$'

echo "=== I. Anti-upload PHP dans images/ (8081) ==="
printf '<?php echo "pwn"; ?>' > /app/images/quelconque.php
chk 403 "$SUB/images/quelconque.php" "SUB images/quelconque.php"
chk 403 "$ROOT/images/quelconque.php" "ROOT images/quelconque.php"
rm -f /app/images/quelconque.php

echo "=== J. Contact form POST ==="
for B in "$SUB" "$ROOT"; do
  CK=/tmp/ck_$(echo "$B" | md5sum | cut -c1-6).txt; rm -f "$CK"
  code=$(curl -s -o /dev/null -w "%{http_code}" -c "$CK" -X POST \
    -d "name=TESTNom&surname=TESTPrenom&ville=Lille&cp=59000&email=test@example.com&tel=0600000000&message=Message+de+test+it6" \
    "$B/formulaire_contact.php")
  if [ "$code" == "302" ]; then echo "PASS 302 POST $B"; PASS=$((PASS+1)); else echo "FAIL POST $B got $code"; FAIL=$((FAIL+1)); FAILS+=("POST $B got=$code"); fi
  grep -q PHPSESSID "$CK" && { echo "PASS session cookie $B"; PASS=$((PASS+1)); } || { echo "FAIL no cookie $B"; FAIL=$((FAIL+1)); FAILS+=("cookie $B"); }
  FB=$(curl -s -b "$CK" "$B/fbf_contact.php" | grep -o 'data-testid="contact-feedback-[a-z]*"' | head -1)
  echo "   feedback: $FB"
  [ -n "$FB" ] && { echo "PASS feedback $B"; PASS=$((PASS+1)); } || { echo "FAIL feedback missing $B"; FAIL=$((FAIL+1)); FAILS+=("feedback $B"); }
done

echo "=== K. Pas de fuite Emergent (source) ==="
HITS=$(grep -rniE "emergent|preview\.emergentagent|/app/" /app/*.php /app/accueil/*.php /app/assets /app/.htaccess /app/images/.htaccess 2>/dev/null)
if [ -z "$HITS" ]; then echo "PASS aucune fuite"; PASS=$((PASS+1)); else echo "FAIL fuites:"; echo "$HITS"; FAIL=$((FAIL+1)); FAILS+=("emergent leak"); fi

echo "=== K2. Pas de fuite dans HTML rendu ==="
for u in "$SUB/" "$SUB/fbf_decouverte.php" "$ROOT/"; do
  if curl -s "$u" | grep -qiE "emergent|/app/"; then echo "FAIL leak in $u"; FAIL=$((FAIL+1)); FAILS+=("leak $u"); else echo "PASS no leak $u"; PASS=$((PASS+1)); fi
done

echo "=== Apache error log tail ==="
tail -n 15 /var/log/apache2/error.log 2>/dev/null

echo "==============================="
echo "PASS=$PASS FAIL=$FAIL"
printf '%s\n' "${FAILS[@]}"
