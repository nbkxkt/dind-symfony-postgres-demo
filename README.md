# DIND Symfony + PostgreSQL demo

A small Symfony 7.4 LTS application that reads fictional rows from PostgreSQL. It is built and run as a nested container inside the `mgs-dind` Docker-in-Docker engine.

The app and database share an internal Docker network. PostgreSQL has no published port, and the application source contains no server address or deployment credential. The seeded readings are synthetic.

## Runtime

- Symfony 7.4 LTS, PHP 8.4, Doctrine DBAL and Migrations, Twig.
- PostgreSQL 16 in the inner Docker engine.
- `/` queries `demo_entry` and renders the result.
- `/healthz` checks PostgreSQL and returns a small JSON status.
- The migration creates the table and inserts three fictional readings.

Deployment instructions and the private runtime `.env` generation are in the companion `dind-symfony-postgres-infra` repository. Never commit `.env` or credentials.

## Automatic deployment

A push to branch `dev` starts the GitHub Actions workflow in `.github/workflows/deploy-dind.yml`. The workflow sends the repository, branch and commit SHA in an HMAC-signed HTTPS request. Configure these repository Actions secrets before enabling pushes: `DEPLOY_WEBHOOK_URL` (the deployment endpoint URL) and `DEPLOY_WEBHOOK_SECRET` (a random secret of at least 32 bytes). Their values must not be committed.

The host-side receiver accepts only this repository and `refs/heads/dev`, verifies the signature, fetches the requested commit and rebuilds only the existing `app` Compose service. It refuses to deploy when the DIND or existing PostgreSQL service is unhealthy or when the app checkout has local changes. It does not create a second app or restart PostgreSQL.
