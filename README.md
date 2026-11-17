# DIND Symfony + PostgreSQL demo

A small Symfony 7.4 LTS application that reads fictional rows from PostgreSQL. It is built and run as nested containers inside the `mgs-dind` Docker-in-Docker engine on VPS 167.

The app and database share a Docker network marked `internal: true`. The Compose file publishes no ports. PostgreSQL has no host mapping, and this project defines no SGE, Kajy, public URL, reverse proxy, or webhook route. The only remote source is this public GitHub repository; the seeded readings are synthetic.

## Runtime

- Symfony 7.4 LTS, PHP 8.4, Doctrine DBAL and Migrations, Twig.
- PostgreSQL 16 in the inner Docker engine.
- `/` queries `demo_entry` and renders the result.
- `/healthz` checks PostgreSQL and returns a small JSON status.
- The migration creates the table and inserts three fictional readings.

Deployment instructions and the private runtime `.env` generation are in the companion `dind-symfony-postgres-infra` repository. Never commit `.env` or credentials.
