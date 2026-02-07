#!/usr/bin/env bash
set -euo pipefail

http_code() {
  docker exec php-apache-container sh -lc "curl -s -o /dev/null -w '%{http_code}' \"$1\""
}

mysql_root() {
  docker exec -e MYSQL_PWD=root mysql-container mysql -uroot "$@"
}

mysqladmin_root() {
  docker exec -e MYSQL_PWD=root mysql-container mysqladmin -uroot "$@"
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

echo "[e2e] reseed db"
for _ in {1..20}; do
  if docker exec -i -e MYSQL_PWD=root mysql-container mysql -uroot < database/init.sql >/dev/null 2>&1; then
    break
  fi
  sleep 1
done

echo "[e2e] basic routes"
expect_code "http://localhost/" "302"
expect_code "http://localhost/auth/login.php" "200"
expect_code "http://localhost/recipes/home.php" "302"

echo "[e2e] login"
docker exec php-apache-container sh -lc "rm -f /tmp/cookies.txt /tmp/login.html"
code="$(docker exec php-apache-container sh -lc "curl -s -L -o /tmp/login.html -c /tmp/cookies.txt -b /tmp/cookies.txt -w '%{http_code}' -X POST -d 'email=mickael.andrieu@exemple.com&password=devine' http://localhost/auth/login_submit.php")"
echo "[e2e] POST /auth/login_submit.php => ${code} (expected 200)"
test "$code" = "200"

echo "[e2e] authenticated home"
code="$(docker exec php-apache-container sh -lc "curl -s -o /tmp/home.html -b /tmp/cookies.txt -c /tmp/cookies.txt -w '%{http_code}' http://localhost/recipes/home.php")"
echo "[e2e] GET /recipes/home.php => ${code} (expected 200)"
test "$code" = "200"

echo "[e2e] create recipe"
code="$(docker exec php-apache-container sh -lc "curl -s -L -o /tmp/create.html -b /tmp/cookies.txt -c /tmp/cookies.txt -w '%{http_code}' -X POST -d 'title=E2E+Recipe&recipe=Hello+World' http://localhost/recipes/store.php")"
echo "[e2e] POST /recipes/store.php => ${code} (expected 200)"
test "$code" = "200"
docker exec php-apache-container sh -lc "grep -q 'E2E Recipe' /tmp/create.html"

echo "[e2e] comment recipe"
recipe_id="$(mysql_root -N -B -e 'SELECT MAX(recipe_id) FROM fooddb.recipes')"
test -n "$recipe_id"
code="$(docker exec php-apache-container sh -lc "curl -s -L -o /tmp/comment.html -b /tmp/cookies.txt -c /tmp/cookies.txt -w '%{http_code}' -X POST -d 'recipe_id=${recipe_id}&review=5&comment=Great+recipe' http://localhost/comments/store.php")"
echo "[e2e] POST /comments/store.php => ${code} (expected 200)"
test "$code" = "200"
docker exec php-apache-container sh -lc "grep -q 'Great recipe' /tmp/comment.html"

echo "e2e ok"
