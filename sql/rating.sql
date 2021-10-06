create table rating (ratingid int(50) auto_increment, pkid int(50), userid int(50), ratingvalue decimal, primary key(ratingid), foreign key (pkid) references produk_kulit(pkid) on delete set null, foreign key (userid) references user(userid) on delete set null)

select user.nama as user, produk_kulit.namapk as produk, rating.ratingvalue as rating from rating right join user on rating.userid=user.userid right join produk_kulit on rating.pkid=produk_kulit.pkid order by user.userid,produk_kulit.pkid asc

SELECT A.userid, A.nama, B.pkid, C.ratingvalue FROM user A LEFT JOIN rating C ON A.userid = C.userid INNER JOIN produk_kulit B ON B.pkid = C.pkid order by A.nama, B.pkid asc