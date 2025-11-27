CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "two_factor_secret" text,
  "two_factor_recovery_codes" text,
  "two_factor_confirmed_at" datetime
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "servers"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "host" varchar not null,
  "port" integer not null default '22',
  "username" varchar not null,
  "auth_type" varchar check("auth_type" in('password', 'key')) not null default 'password',
  "private_key" text,
  "password" varchar,
  "is_active" tinyint(1) not null default '1',
  "connection_options" text,
  "created_at" datetime,
  "updated_at" datetime,
  "last_connected_at" datetime,
  "server_details" text,
  "cpu_usage" numeric,
  "memory_usage" varchar,
  "disk_usage" varchar,
  "os_info" varchar,
  "kernel_version" varchar,
  "uptime" varchar,
  "last_detail_fetch_at" datetime
);
CREATE TABLE IF NOT EXISTS "command_histories"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "server_id" integer not null,
  "command" text not null,
  "current_directory" varchar,
  "execution_time" numeric,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("server_id") references "servers"("id") on delete cascade
);
CREATE INDEX "command_histories_user_id_server_id_created_at_index" on "command_histories"(
  "user_id",
  "server_id",
  "created_at"
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_09_22_145432_add_two_factor_columns_to_users_table',1);
INSERT INTO migrations VALUES(5,'2025_11_24_191200_create_servers_table',1);
INSERT INTO migrations VALUES(6,'2025_11_24_202630_add_last_connected_at_to_servers_table',1);
INSERT INTO migrations VALUES(7,'2025_11_24_202636_add_last_connected_at_to_servers_table',1);
INSERT INTO migrations VALUES(8,'2025_11_26_154058_create_command_histories_table',2);
INSERT INTO migrations VALUES(9,'2025_11_26_171029_add_server_details_to_servers_table',2);
