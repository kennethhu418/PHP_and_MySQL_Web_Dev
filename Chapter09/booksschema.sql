create table Customers
(
    CustomerID int unsigned not null auto_increment primary key,
    Name char(30) not null,
    Address char(150),
    City char(30) not null
);

create table Orders
(
    OrderID int unsigned not null auto_increment primary key,
    CustomerID  int unsigned not null,
    Amount  float(8,1),
    Date date not null
);

create table Books
(
    ISBN char(13) not null primary key,
    Author char(30),
    Title char(100),
    Price float(4,1)
);

create table Order_Items
(
    OrderID int unsigned not null,
    ISBN    char(13),
    Quantity int unsigned not null,
    primary key(OrderID, ISBN)
);

create table Book_Reviews
(
    ISBN char(13) not null primary key,
    Review text not null
);

