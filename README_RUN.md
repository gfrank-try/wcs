# Run instructions

This scaffold will create a Symfony 5 backend and an Angular frontend the first time you run the containers.

Prerequisites: Docker and Docker Compose installed.

From the project root run:

```bash
docker compose build
docker compose up
```

What happens:
- The `db` service runs PostgreSQL.
- The `backend` container will create a Symfony 5 skeleton in `./backend` on first startup and start the PHP built-in server on port 8000.
- The `frontend` container will create an Angular project in `./frontend` on first startup and run `ng serve` on port 4200.

Notes:
- The first run downloads and installs many packages; it may take several minutes.
- After the project files are created you can stop the containers and work on files locally in `./backend` and `./frontend`.
- To connect Symfony to the database, the `DATABASE_URL` environment variable is passed automatically by docker-compose.
