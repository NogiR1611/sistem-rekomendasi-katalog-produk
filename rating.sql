create table rating (ratingid int(50) auto_increment, pkid int(50), userid int(50), ratingvalue decimal, primary key(ratingid), foreign key (pkid) references produk_kulit(pkid), foreign key (userid) references user(userid))