#!/usr/bin/env bash
set -euo pipefail

http_code() {
  docker exec php-apache-container sh -lc "curl -s -o /dev/null -w '%{http_code}' \"$1\""
}

root_pass() {
  local pass
  pass="$(docker inspect mysql-container --format '{{range .Config.Env}}{{println .}}{{end}}' | sed -n 's/^MYSQL_ROOT_PASSWORD=//p' | head -n 1)"
  if [[ -z "${pass}" ]]; then
    pass="root"
  fi
  echo "${pass}"
}

mysql_root() {
  local pass
  pass="$(root_pass)"
  docker exec -e MYSQL_PWD="${pass}" mysql-container mysql -uroot "$@"
}

mysqladmin_root() {
  local pass
  pass="$(root_pass)"
  docker exec -e MYSQL_PWD="${pass}" mysql-container mysqladmin -uroot "$@"
}

expect_code() {
  local url="$1"
  local expected="$2"
  local got
  got="$(http_code "$url")"
  echo "[e2e] GET ${url} => ${got} (expected ${expected})"
  test "$got" = "$expected"
}

wait_mysql() {
  for _ in {1..60}; do
    if mysqladmin_root ping --silent >/dev/null 2>&1; then
      if mysql_root -N -B -e 'SELECT 1' >/dev/null 2>&1; then
        return 0
      fi
    fi
    sleep 1
  done
  echo "MySQL not ready" >&2
  docker logs mysql-container || true
  return 1
}

echo "[e2e] wait mysql"
wait_mysql

echo "[e2e] seed db"
echo "[e2e] mysql root password found: $(root_pass | sed 's/./*/g')"
cat sql/init.sql | mysql_root >/dev/null

echo "[e2e] basic routes"
expect_code "http://localhost/" "302"
expect_code "http://localhost/login/login.php" "200"
expect_code "http://localhost/home.php" "302"

echo "[e2e] login"
docker exec php-apache-container sh -lc "rm -f /tmp/cookies.txt /tmp/login.html"
code="$(docker exec php-apache-container sh -lc "curl -s -L -o /tmp/login.html -c /tmp/cookies.txt -b /tmp/cookies.txt -w '%{http_code}' -X POST -d 'email=mickael.andrieu@exemple.com&password=devine' http://localhost/login/submit_login.php")"
echo "[e2e] POST /login/submit_login.php => ${code} (expected 200)"
test "$code" = "200"

echo "[e2e] protected page requires auth"
expect_code "http://localhost/application/recipes_create.php" "302"

echo "[e2e] add recipe"
docker exec php-apache-container sh -lc "rm -f /tmp/create.html"
code="$(docker exec php-apache-container sh -lc "curl -s -o /tmp/create.html -b /tmp/cookies.txt -c /tmp/cookies.txt -w '%{http_code}' -X POST -d 'title=E2E+Recipe&recipe=Hello+World' http://localhost/application/recipes_post_create.php")"
echo "[e2e] POST /application/recipes_post_create.php => ${code} (expected 200)"
test "$code" = "200"
docker exec php-apache-container sh -lc "grep -q \"Recette\" /tmp/create.html"

echo "[e2e] view created recipe"
recipe_id="$(mysql_root -N -B -e 'SELECT MAX(recipe_id) FROM fooddb.recipes')"
echo "[e2e] recipe_id=${recipe_id}"
test -n "$recipe_id"
docker exec php-apache-container sh -lc "curl -s -o /tmp/read.html -b /tmp/cookies.txt http://localhost/application/recipes_read.php?id=${recipe_id}"
docker exec php-apache-container sh -lc "grep -q \"E2E Recipe\" /tmp/read.html"

echo "[e2e] content_unique alias"
code="$(docker exec php-apache-container sh -lc "curl -s -o /dev/null -w '%{http_code}' 'http://localhost/content_unique.php?id=1'")"
echo "[e2e] GET /content_unique.php?id=1 => ${code} (expected 200)"
test "$code" = "200"

echo "e2e ok"
