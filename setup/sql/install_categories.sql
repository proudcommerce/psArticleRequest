-- psArticleRequest 3.x.x
create table psarticlerequest_categories
(
    OXID        char(32)                            not null,
    OXCATNID    char(32)                            null,
    OXTIMESTAMP timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint psarticlerequest_categories_OXCATNID_uindex
        unique (OXCATNID),
    constraint psarticlerequest_categories_OXID_uindex
        unique (OXID)
)
    collate = latin1_general_ci;

alter table psarticlerequest_categories
    add primary key (OXID);