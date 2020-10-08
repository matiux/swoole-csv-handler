Swoole CSV handler
===

## Sviluppo

```
git clone ?? && cd swoole-csv-handler
cp docker/docker-compose.override.dist.yml docker/docker-compose.override.yml
rm -rf .git/hooks && ln -s ../scripts/git-hooks .git/hooks
```

#### Entrare nel container PHP per lo sviluppo
```
./dc up -d
./dc enter
composer install
```
