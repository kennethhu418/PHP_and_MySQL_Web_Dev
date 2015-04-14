use books;

insert LOW_PRIORITY into Customers values
  (NULL, "Julie Smith", "25 Oak Street", "Airport West"),
  (NULL, "Alan Wong", "1/47 Haines Avenue", "Box Hill"),
  (NULL, "Michelle Arthur", "357 North Road", "Yarraville");

insert LOW_PRIORITY into Orders values
  (NULL, 3, 69.98, "2015-04-02"),
  (NULL, 1, 49.99, "2015-04-05"),
  (NULL, 2, 74.98, "2015-04-01"),
  (NULL, 3, 24.99, "2015-03-01");

insert LOW_PRIORITY into Books values
  ("0-672-31697-8", "Michael Morgan", "Java 2 for Professional Developers", 34.99),
  ("0-672-31745-1", "Thomas Down", "Installing Debian GNU/Linux", 24.99),
  ("0-672-31509-2", "Pruitt, et al.", "Teach Yourself GIMP in 24 Hours", 24.99),
  ("0-672-31769-9", "Thomas Schenk", "Caldera OpenLinux System Administration Unleashed", 49.99);

insert LOW_PRIORITY into Order_Items values
  (1, "0-672-31697-8", 2),
  (2, "0-672-31769-9", 1),
  (3, "0-672-31769-9", 1),
  (3, "0-672-31509-2", 1),
  (4, "0-672-31745-1", 3);

insert LOW_PRIORITY into Book_Reviews values
("0-672-31697-8", "Morgan's book is clearly written and goes well beyond most of the basic Java books out there."), ("0-672-31509-2", "Wow, this is really an amazing book. I really learned GIMP within 24 hours and can practice it freely!!!"); 
