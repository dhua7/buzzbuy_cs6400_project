-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';


-- >> Jae's note << 
-- Team, please use this script just ONCE to create a new database in your local machine
-- Once a new database gets created, use our "Data Seed" script to upload test data 

				
-- This drops an existing table "cs6400_summer24_team034" first before creating a new database
-- 
DROP DATABASE IF EXISTS `cs6400_summer24_team034`; 
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_summer24_team034 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_summer24_team034;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_summer24_team034`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;


CREATE TABLE District (
    DistrictNumber INT PRIMARY KEY NOT NULL
);


CREATE TABLE City (
    CityName VARCHAR(255) NOT NULL,
    State VARCHAR(255) NOT NULL,
    Population INT NULL,
    PRIMARY KEY (CityName,State)
);

CREATE TABLE Store (
StoreNumber INT PRIMARY KEY NOT NULL, 
PhoneNumber VARCHAR(255) UNIQUE NOT NULL, 
DistrictNumber INT NOT NULL, 
CityName VARCHAR(255) NOT NULL, 
State VARCHAR(255) NOT NULL, 
FOREIGN KEY (DistrictNumber) REFERENCES District(DistrictNumber), 
FOREIGN KEY (CityName, State) REFERENCES City(CityName, State) 
);


CREATE TABLE Manufacturer (
    ManufacturerName VARCHAR(255) PRIMARY KEY NOT NULL
);

CREATE TABLE Product (
    PID VARCHAR(50) PRIMARY KEY NOT NULL,
    ProductName VARCHAR(255) NULL,
    ManufacturerName VARCHAR(255) NOT NULL,
    RetailPrice DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ManufacturerName) REFERENCES Manufacturer(ManufacturerName)
);


CREATE TABLE Category (
    CategoryName VARCHAR(255) PRIMARY KEY NOT NULL
);

CREATE TABLE Assignto (
    PID VARCHAR(50) NOT NULL,
    CategoryName VARCHAR(255) NOT NULL,
    PRIMARY KEY (PID, CategoryName), 
    FOREIGN KEY (PID) REFERENCES Product(PID),
    FOREIGN KEY (CategoryName) REFERENCES Category(CategoryName)
);


CREATE TABLE BusinessDay ( 
    BusinessDate DATE PRIMARY KEY NOT NULL
);


CREATE TABLE Discount (
    PID VARCHAR(50) NOT NULL, 
    BusinessDate DATE NOT NULL, 
    DiscountPrice DECIMAL(10, 2) NOT NULL, 
    PRIMARY KEY (PID, BusinessDate),
    FOREIGN KEY (PID) REFERENCES Product(PID), 
    FOREIGN KEY (BusinessDate) REFERENCES BusinessDay(BusinessDate) 

);


CREATE TABLE Sells (
    StoreNumber INT NOT NULL, 
    PID VARCHAR(50) NOT NULL, 
    QuantitySold INT (10) NOT NULL,
    DateSold DATE NOT NULL, 
    PRIMARY KEY (StoreNumber, PID, DateSold),
    FOREIGN KEY (StoreNumber) REFERENCES Store(StoreNumber), 
    FOREIGN KEY (PID) REFERENCES Product(PID), 
    FOREIGN KEY (DateSold) REFERENCES BusinessDay(BusinessDate) 

);


CREATE TABLE Holidays (
BusinessDate DATE PRIMARY KEY NOT NULL,
HolidayName VARCHAR(255) 
);

CREATE TABLE `User` (
    EmployeeID VARCHAR(7) PRIMARY KEY NOT NULL, 
    FirstName VARCHAR(255) NOT NULL, 
    LastName VARCHAR(255) NOT NULL, 
    LastFourSSN VARCHAR(4) NOT NULL, 
    AccessToAuditLog BOOLEAN DEFAULT FALSE 

);

CREATE TABLE Created (
    EmployeeID VARCHAR(7) NOT NULL,
    BusinessDate DATE NOT NULL,
    FOREIGN KEY (EmployeeID) REFERENCES User(EmployeeID),
    FOREIGN KEY (BusinessDate) REFERENCES BusinessDay(BusinessDate)
);

CREATE TABLE CanAccess (
    EmployeeID VARCHAR(7) NOT NULL,
    DistrictNumber INT (10) NOT NULL,
    FOREIGN KEY (EmployeeID) REFERENCES User(EmployeeID),
    FOREIGN KEY (DistrictNumber) REFERENCES District(DistrictNumber)
);

CREATE TABLE Report (
    ReportName VARCHAR(255) PRIMARY KEY NOT NULL
);

CREATE TABLE AuditLogEntry (
    EmployeeID VARCHAR(7) NOT NULL, 
    Timestamp TIMESTAMP NOT NULL, 
    ReportName VARCHAR(255) NOT NULL,
    PRIMARY KEY (EmployeeID, Timestamp, ReportName),  
    FOREIGN KEY (EmployeeID) REFERENCES User(EmployeeID), 
    FOREIGN KEY (ReportName) REFERENCES Report(ReportName) 

);
