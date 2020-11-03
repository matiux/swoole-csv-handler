Swoole CSV handler - POC
===

## Sviluppo

```
git clone git@github.com:matiux/swoole-csv-handler.git && cd swoole-csv-handler
cp docker/docker-compose.override.dist.yml docker/docker-compose.override.yml
rm -rf .git/hooks && ln -s ../scripts/git-hooks .git/hooks
```

#### Preparare l'ambiente
```bash
cp frontend/envs/.env.example frontend/.env
cp docker/docker-compose.override.dist.yml docker/docker-compose.override.yml
cp Http/rest-client.private.env.json.dist Http/rest-client.private.env.json
```
#### Installare le dipendenze
```bash
./dc composer install
./dc run nodejs /bin/sh -c "cd frontend && yarn install"
```

#### Lanciare il server Swoole (shell 1)
```shell
./dc up [-d]
```
#### Lanciare il server node (shell 2)
```shell
./dc run -p 3075:3075 nodejs /bin/sh -c "cd frontend && yarn run dev"
```
