# PTW - Productive Time Waste

## Installing

**Required** - [Docker](https://www.docker.com/)

```bash
git clone https://github.com/dragi-ns/ptw.git
cd ptw
docker compose up -d
```

**Note** - After running `docker compose up -d` you have to wait a few seconds to be able to connect to MySQL instance.

- Homepage: http://localhost
- Adminer: http://localhost:8080
  - System: MySQL
  - Server: `db`
  - Username: `dragi-ns`
  - Password: `password`
  - Database: `ptw`
- MySQL: localhost:3306

## Demo

![](./demo/demo.gif)
