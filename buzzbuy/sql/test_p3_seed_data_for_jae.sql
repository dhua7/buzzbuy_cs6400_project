
USE cs6400_summer24_team034 ;

-- Inserting Users --
INSERT INTO `User` (employeeid,firstname,lastname,lastfourssn,accesstoauditlog) VALUES ('0001','User0001','Douglas', 1234, 1);
INSERT INTO `User` (employeeid,firstname,lastname,lastfourssn,accesstoauditlog) VALUES ('0002','User0002','Douglas', 5678, 1);
INSERT INTO `User` (employeeid,firstname,lastname,lastfourssn,accesstoauditlog) VALUES ('0003','User0003','Douglas', 1234, 1);
INSERT INTO `User` (employeeid,firstname,lastname,lastfourssn,accesstoauditlog) VALUES ('0004','User0004','Douglas', 5678, 1);


-- inserting 9 Holidays --
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2019-02-18','Presidents Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2019-01-21','Martin Luther King Jr. Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2019-01-01','New Years Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2018-02-19','Presidents Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2018-01-15','Martin Luther King Jr. Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2018-01-01','New Years Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2017-02-20','Presidents Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2017-01-16','Martin Luther King Jr. Day');
INSERT INTO Holidays (BusinessDate, HolidayName) VALUES ('2017-01-01','New Years Day');


INSERT INTO Report (ReportName) VALUES ('Actual versus Predicted Revenue for GPS units');
INSERT INTO Report (ReportName) VALUES ('Air Conditioners on Groundhog Day?');
INSERT INTO Report (ReportName) VALUES ('Category Report');
INSERT INTO Report (ReportName) VALUES ('District with Highest Volume for each Category');
INSERT INTO Report (ReportName) VALUES ('Manufacturer''s Product Report');
INSERT INTO Report (ReportName) VALUES ('Revenue by Population');
INSERT INTO Report (ReportName) VALUES ('Store Revenue by Year by State');