DROP TABLE IF EXISTS "user";
CREATE TABLE "public"."user" (
    "id" integer NOT NULL,
    "username" character varying(180) NOT NULL,
    "roles" json NOT NULL,
    "password" character varying(255) NOT NULL,
    "firstname" character varying(50) NOT NULL,
    "lastname" character varying(50) NOT NULL,
    "email" character varying(50) NOT NULL,
    "is_verified" boolean NOT NULL,
    CONSTRAINT "uniq_8d93d649f85e0677" UNIQUE ("username"),
    CONSTRAINT "user_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "user" ("id", "username", "roles", "password", "firstname", "lastname", "email", "is_verified") VALUES
(1,	'admin',	'["ROLE_ADMIN"]',	'$2y$13$dqBFqJVrcRt0.flhyuCoWeCBOweueIks0GEE4jsVlKy8OsGmaqBFm',	'',	'',	'admin@trtconseil.com',	'1');