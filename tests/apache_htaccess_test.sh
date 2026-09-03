#!/bin/bash
# Apache / .htaccess validation suite for Bois Frican (XAMPP + OVH compat)
BASE="http://127.0.0.1:8080"
PASS=0; FAIL=0
chk() { # chk <expected> <url> <label>
  code=$(curl -s -o /tmp/body.out -w "%{http_code}" "$2")
  if [ "$code" == "$1" ]; then echo "PASS [$code] $3"; PASS=$((PASS+1));
  else echo "FAIL [got $code want $1] $3"; FAIL=$((FAIL+1)); fi
}
grepbody() { # grepbody <url> <pattern> <label>
  if curl -s "$1" | grep -qi -- "$2"; then echo "PASS grep '$2' in $3"; PASS=$((PASS+1));
  else echo "FAIL grep '$2' MISSING in $3"; FAIL=$((FAIL+1)); fi
}
nogrepbody() {
  if curl -s "$1" | grep -qi -- "$2"; then echo "FAIL leak '$2' found in $3"; FAIL=$((FAIL+1));
  else echo "PASS no '$2' in $3"; PASS=$((PASS+1)); fi
}

echo "=== 1. Home page 200 + title ==="
chk 200 "$BASE/" "GET /"
grepbody "$BASE/" "Bois Frican" "/"

echo "=== 2. All pages at root ==="
for p in fbf_apropos.php fbf_contact.php fbf_decouverte.php fbf_infopratique.php fbf_mentionslegales.php accueil/fbf_accueil.php; do
  chk 200 "$BASE/$p" "GET /$p"
done
grepbody "$BASE/fbf_contact.php" "Contact" "fbf_contact.php h1"
grepbody "$BASE/fbf_apropos.php" "propos" "fbf_apropos.php h1"
grepbody "$BASE/fbf_infopratique.php" "ratique" "fbf_infopratique.php"
grepbody "$BASE/fbf_mentionslegales.php" "entions" "fbf_mentionslegales.php"
grepbody "$BASE/fbf_decouverte.php" "couverte" "fbf_decouverte.php"
for p in "" fbf_apropos.php fbf_contact.php; do
  grepbody "$BASE/$p" "Accueil" "nav in /$p"
done

echo "=== 3. Subfolder /bois-frican/ ==="
for p in "" fbf_contact.php fbf_apropos.php accueil/fbf_accueil.php fbf_mentionslegales.php fbf_decouverte.php fbf_infopratique.php; do
  chk 200 "$BASE/bois-frican/$p" "GET /bois-frican/$p"
done
grepbody "$BASE/bois-frican/" "fbf_style_test.css" "css link subfolder home"
grepbody "$BASE/bois-frican/fbf_contact.php" "fbf_style_test.css" "css link subfolder contact"

echo "=== 4. Security: sensitive files 403 ==="
for f in .env.example composer.json composer.lock .htaccess .gitignore README.md test_result.md .gitattributes; do
  chk 403 "$BASE/$f" "GET /$f"
done
for d in vendor memory test_reports backend frontend; do
  chk 403 "$BASE/$d/" "GET /$d/"
done
chk 403 "$BASE/vendor/autoload.php" "GET /vendor/autoload.php"

echo "=== 5. Anti-upload PHP in /images/ ==="
printf '<?php echo "hello"; ?>' > /app/images/pwn.php
chk 403 "$BASE/images/pwn.php" "GET /images/pwn.php"
rm -f /app/images/pwn.php

echo "=== 6. Static assets ==="
for a in fbf_style_test.css assets/js/menu.js; do
  chk 200 "$BASE/$a" "GET /$a"
done
echo "CT css: $(curl -s -o /dev/null -w '%{content_type}' $BASE/fbf_style_test.css)"
echo "CT js : $(curl -s -o /dev/null -w '%{content_type}' $BASE/assets/js/menu.js)"
IMG=$(ls /app/images | grep -iE '\.(png|jpg|jpeg|webp)$' | head -1)
echo "image sample: $IMG"
chk 200 "$BASE/images/$IMG" "GET /images/$IMG"
echo "CT img: $(curl -s -o /dev/null -w '%{content_type}' "$BASE/images/$IMG")"

echo "=== 7. Contact form POST (root) ==="
rm -f /tmp/ck.txt
code=$(curl -s -o /tmp/post.out -w "%{http_code}" -c /tmp/ck.txt -X POST \
  -d "name=TESTNom&surname=TESTPrenom&ville=Lille&cp=59000&email=test@example.com&tel=0600000000&message=Message+de+test+automatise" \
  "$BASE/formulaire_contact.php")
echo "POST /formulaire_contact.php -> $code"
[ "$code" == "302" ] && { echo "PASS 302 redirect"; PASS=$((PASS+1)); } || { echo "FAIL expected 302"; FAIL=$((FAIL+1)); }
grep -q PHPSESSID /tmp/ck.txt && { echo "PASS session cookie set"; PASS=$((PASS+1)); } || { echo "FAIL no session cookie"; FAIL=$((FAIL+1)); }
echo "--- Location header:"; curl -s -o /dev/null -D - -X POST -d "name=T&surname=T&ville=L&cp=59000&email=t@e.com&tel=0600000000&message=hello" "$BASE/formulaire_contact.php" 2>/dev/null | grep -i location
FB=$(curl -s -b /tmp/ck.txt "$BASE/fbf_contact.php" | grep -o 'data-testid="contact-feedback-[a-z]*"' | head -1)
echo "feedback testid (root): $FB"
[ -n "$FB" ] && { echo "PASS feedback shown"; PASS=$((PASS+1)); } || { echo "FAIL no feedback element"; FAIL=$((FAIL+1)); }

echo "=== 7b. Contact form POST (subfolder) ==="
rm -f /tmp/ck2.txt
code=$(curl -s -o /dev/null -w "%{http_code}" -c /tmp/ck2.txt -X POST \
  -d "name=TESTNom&surname=TESTPrenom&ville=Lille&cp=59000&email=test@example.com&tel=0600000000&message=Message+sous+dossier" \
  "$BASE/bois-frican/formulaire_contact.php")
echo "POST /bois-frican/formulaire_contact.php -> $code"
[ "$code" == "302" ] && { echo "PASS 302"; PASS=$((PASS+1)); } || { echo "FAIL expected 302"; FAIL=$((FAIL+1)); }
FB2=$(curl -s -b /tmp/ck2.txt "$BASE/bois-frican/fbf_contact.php" | grep -o 'data-testid="contact-feedback-[a-z]*"' | head -1)
echo "feedback testid (subfolder): $FB2"
[ -n "$FB2" ] && { echo "PASS feedback shown subfolder"; PASS=$((PASS+1)); } || { echo "FAIL no feedback subfolder"; FAIL=$((FAIL+1)); }

echo "=== 8. No Emergent leakage in rendered HTML ==="
for u in "$BASE/" "$BASE/fbf_contact.php" "$BASE/bois-frican/"; do
  nogrepbody "$u" "emergent" "$u"
  nogrepbody "$u" "/app/" "$u"
  nogrepbody "$u" "preview.emergentagent" "$u"
done

echo "=== 10. Windows compat: no hardcoded unix paths in deliverable PHP ==="
HITS=$(grep -rnE "'/app/|\"/app/|/var/www/|/home/" /app/*.php /app/accueil/*.php /app/views 2>/dev/null)
if [ -z "$HITS" ]; then echo "PASS no absolute unix paths"; PASS=$((PASS+1)); else echo "FAIL:"; echo "$HITS"; FAIL=$((FAIL+1)); fi
echo "--- include/require statements:"
grep -rnE "(include|require)(_once)?[ (]" /app/*.php /app/accueil/*.php 2>/dev/null

echo "=== Apache error log tail ==="
tail -n 20 /var/log/apache2/error.log 2>/dev/null || tail -n 20 /var/log/apache2/*error*.log 2>/dev/null

echo "==============================="
echo "PASS=$PASS FAIL=$FAIL"
