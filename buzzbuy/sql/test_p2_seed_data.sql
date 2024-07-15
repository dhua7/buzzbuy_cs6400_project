
USE cs6400_summer24_team034 ;

-- Inserting Users --
INSERT INTO User (employeeid,firstname,lastname,lastfourssn,accesstoauditlog) VALUES ('0001','User0001','Douglas', 1234, 1);
INSERT INTO User (employeeid,firstname,lastname,lastfourssn,accesstoauditlog) VALUES ('0002','User0002','Douglas', 5678, 1);
INSERT INTO User (employeeid,firstname,lastname,lastfourssn,accesstoauditlog) VALUES ('0003','User0003','Douglas', 1234, 1);
INSERT INTO User (employeeid,firstname,lastname,lastfourssn,accesstoauditlog) VALUES ('0004','User0004','Douglas', 5678, 1);


--inserting 9 Holidays--
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2019-02-18','2019 Presidents Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2019-01-21','2019 Martin Luther King Jr. Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2019-01-01','2019 New Years Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2018-02-19','2018 Presidents Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2018-01-15','2018 Martin Luther King Jr. Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2018-01-01','2018 New Years Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2017-02-20','2017 Presidents Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2017-01-16','2017 Martin Luther King Jr. Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2017-01-01','2017 New Years Day');