/*
https://stackoverflow.com/questions/4107915/postgresql-default-constraint-names

The standard names for indexes in PostgreSQL are:

{tablename}_{columnname(s)}_{suffix}

where the suffix is one of the following:

    pkey - for a Primary Key constraint
    key - for a Unique constraint
    excl - for an Exclusion constraint
    idx - for any other kind of index
    fkey - for a Foreign key
    check - for a Check constraint

Standard suffix for sequences is
    seq - for all sequences

Worth adding that foreign keys use the suffix fkey and that multi-column foreign key constraints
only seem to include the first column name.
*/

CREATE TABLE rank (
    id SERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    image TEXT,
    special BOOL DEFAULT(FALSE),
    min_posts INTEGER DEFAULT(0)
);

CREATE INDEX rank_min_posts_idx ON rank USING btree(min_posts);

CREATE TABLE "user" (
    id SERIAL PRIMARY KEY,
    external_id TEXT NOT NULL, -- Unique, consistent ID from the OpenID Connect provider
    display_name TEXT NOT NULL, -- Should this be unique?
    rank INTEGER,
    email TEXT NOT NULL, -- Should this be unique?
    signature TEXT,
    signature_xml TEXT,
    locale VARCHAR(5) DEFAULT 'en-us', -- FIXME: Change the length of this to fit locales
    timezone VARCHAR(40) DEFAULT 'UTC', -- Max length of values from PHP DateTimeZone::listIdentifiers is 30
    topics INTEGER DEFAULT(0),
    posts INTEGER DEFAULT(0),
    registration_ip INET,
    last_ip INET,
    when_created TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    last_visit TIMESTAMP WITH TIME ZONE,
    last_post TIMESTAMP WITH TIME ZONE,

    CONSTRAINT user_external_id_key UNIQUE (external_id),
    CONSTRAINT user_rank_fkey FOREIGN KEY (rank) references rank(id)
);

-- Is this necessary if we have the unique constraint?
CREATE INDEX user_external_id_idx ON "user" USING hash(external_id);
CREATE INDEX user_email_idx ON "user" USING btree(email);

CREATE TABLE category (
    id SERIAL PRIMARY KEY,
    parent_category INTEGER,
    display_order INTEGER,
    name VARCHAR(126) NOT NULL,
    slug VARCHAR(126) NOT NULL,
    description TEXT,
    topics INTEGER DEFAULT(0),
    posts INTEGER DEFAULT(0),

    CONSTRAINT category_parent_category_fkey FOREIGN KEY (parent_category) REFERENCES category(id),
    CONSTRAINT category_slug_key UNIQUE (slug)
);

CREATE TABLE topic (
    id SERIAL PRIMARY KEY,
    category INTEGER NOT NULL,
    title TEXT NOT NULL,
    author INTEGER NOT NULL,
    replies INTEGER DEFAULT(0),
    when_created TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    last_reply_author INTEGER,
    last_reply TIMESTAMP WITH TIME ZONE,

    CONSTRAINT topic_author_fkey FOREIGN KEY (author) REFERENCES "user"(id),
    CONSTRAINT topicopic_reply_author_fkey FOREIGN KEY (last_reply_author) REFERENCES "user"(id)
);

CREATE INDEX topic_category_idx ON topic USING hash(category);
CREATE INDEX topic_when_created_idx ON topic USING brin(when_created);
CREATE INDEX topic_last_reply_idx ON topic USING brin(last_reply);

CREATE TABLE post (
    id SERIAL PRIMARY KEY,
    topic INTEGER,
    author INTEGER NOT NULL,
    body TEXT NOT NULL,
    body_xml TEXT NOT NULL,
    when_created TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    last_edited TIMESTAMP WITH TIME ZONE,

    CONSTRAINT post_topic_fkey FOREIGN KEY (topic) REFERENCES topic(id),
    CONSTRAINT post_author_fkey FOREIGN KEY (author) REFERENCES "user"(id)
);

CREATE INDEX post_when_created ON post USING brin(when_created);

CREATE TABLE file (
    id SERIAL PRIMARY KEY,
    post INTEGER NOT NULL,
    filename TEXT,
    filesize INTEGER,
    downloads INTEGER DEFAULT(0),
    sha256_hash BYTEA NOT NULL,
    when_created TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),

    CONSTRAINT files_post_fkey FOREIGN KEY (post) REFERENCES post(id)
);

-- TODO: Add when_created timestamp
CREATE TABLE report (
    id SERIAL PRIMARY KEY,
    reported_post INTEGER,
    reported_user INTEGER,
    reported_by INTEGER NOT NULL,
    body TEXT,
    body_xml TEXT,
    when_created TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),

    CONSTRAINT report_reported_post_fkey FOREIGN KEY (reported_post) REFERENCES post(id),
    CONSTRAINT report_reported_user_fkey FOREIGN KEY (reported_user) REFERENCES "user"(id),
    CONSTRAINT report_reported_by_fkey FOREIGN KEY (reported_by) REFERENCES "user"(id)
);

