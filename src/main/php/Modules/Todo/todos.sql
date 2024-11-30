create table todos
(
    id    int auto_increment,
    title VARCHAR(255) not null,
    constraint todos_pk
        primary key (id)
)
    charset = utf16;

