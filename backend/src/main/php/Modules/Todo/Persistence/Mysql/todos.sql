create table if not exists todos
(
    id    int auto_increment,
    title VARCHAR(255) not null,
    constraint todos_pk
        primary key (id)
)
    charset = utf16;

alter table todos
    add column if not exists done TINYINT default 0 not null;
