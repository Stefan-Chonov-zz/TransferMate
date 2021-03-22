create table books
(
    id          serial       not null
        constraint books_pk
            primary key,
    author      varchar(255) not null,
    name        varchar(100) not null,
    created_at  integer      not null,
    modified_at integer      not null
);

-- alter table books
--     owner to transfermate;

