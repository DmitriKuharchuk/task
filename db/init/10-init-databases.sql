-- Idempotent init for extra DBs and users (finance, parser, di)
-- NOTE: CREATE DATABASE cannot be executed inside DO/PLpgSQL.
-- Use psql's \gexec (without trailing semicolon) to conditionally create databases.
-- Roles can be created/updated inside DO blocks.

-- ===== ROLES =====
DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'finance_user') THEN
CREATE ROLE finance_user LOGIN PASSWORD 'finance_password';
ELSE
    ALTER ROLE finance_user WITH LOGIN PASSWORD 'finance_password';
END IF;
END $$;

DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'parser_user') THEN
CREATE ROLE parser_user LOGIN PASSWORD 'parser_password';
ELSE
    ALTER ROLE parser_user WITH LOGIN PASSWORD 'parser_password';
END IF;
END $$;

DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'di_user') THEN
CREATE ROLE di_user LOGIN PASSWORD 'di_password';
ELSE
    ALTER ROLE di_user WITH LOGIN PASSWORD 'di_password';
END IF;
END $$;

-- ===== DATABASES (created only if absent) =====
SELECT 'CREATE DATABASE finance_db OWNER finance_user'
    WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'finance_db') \gexec

SELECT 'CREATE DATABASE parser_db OWNER parser_user'
    WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'parser_db') \gexec

SELECT 'CREATE DATABASE di_db OWNER di_user'
    WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'di_db') \gexec

-- Ensure ownership (safe even if already owner)
ALTER DATABASE finance_db OWNER TO finance_user;
ALTER DATABASE parser_db OWNER TO parser_user;
ALTER DATABASE di_db OWNER TO di_user;

-- ===== GRANTS ON public schema (run inside each DB) =====
\connect finance_db
GRANT ALL ON SCHEMA public TO finance_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO finance_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO finance_user;

\connect parser_db
GRANT ALL ON SCHEMA public TO parser_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO parser_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO parser_user;

\connect di_db
GRANT ALL ON SCHEMA public TO di_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO di_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO di_user;

-- End of file