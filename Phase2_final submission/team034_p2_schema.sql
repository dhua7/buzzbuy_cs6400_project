CREATE TABLE District (
    DistrictNumber INT PRIMARY KEY NOT NULL
);


CREATE TABLE City (
    CityName VARCHAR(255) NOT NULL,
    State VARCHAR(255) NOT NULL,
    Population INT NULL,
    PRIMARY KEY (CityName)
);

CREATE TABLE Store (
StoreNumber INT PRIMARY KEY NOT NULL,
PhoneNumber VARCHAR(255) UNIQUE,
DistrictNumber INT NOT NULL,
CityName VARCHAR(255) NOT NULL,
FOREIGN KEY (DistrictNumber) REFERENCES District(DistrictNumber),
FOREIGN KEY (CityName) REFERENCES City(CityName)
);


CREATE TABLE Manufacturer (
    ManufacturerName VARCHAR(255) PRIMARY KEY NOT NULL
);

CREATE TABLE Product (
    PID INT(50) PRIMARY KEY NOT NULL,
    ProductName VARCHAR(255) NULL,
    ManufacturerName VARCHAR(255) NOT NULL,
    RetailPrice DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ManufacturerName) REFERENCES Manufacturer(ManufacturerName)
);


CREATE TABLE Category (
    CategoryName VARCHAR(255) PRIMARY KEY NOT NULL
);

CREATE TABLE Assignto (
    PID INT(50) NOT NULL,
    CategoryName VARCHAR(255) NOT NULL,
    FOREIGN KEY (PID) REFERENCES Product(PID),
    FOREIGN KEY (CategoryName) REFERENCES Category(CategoryName)
);


CREATE TABLE BusinessDay ( 
    BusinessDate DATE PRIMARY KEY NOT NULL
);


CREATE TABLE Discount (
    PID INT(50) NOT NULL,
    BusinessDate DATE NOT NULL,
    DiscountPrice DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (PID) REFERENCES Product(PID)
);


CREATE TABLE Sells (
    StoreNumber INT NOT NULL,
    PID INT(50) NOT NULL,
    QuantitySold INT (10) NOT NULL,
    DateSold DATE NOT NULL,
    FOREIGN KEY (StoreNumber) REFERENCES Store(StoreNumber),
    FOREIGN KEY (PID) REFERENCES Product(PID)
);


CREATE TABLE Holidays (
BusinessDate DATE PRIMARY KEY NOT NULL,
HolidayName VARCHAR(255) UNIQUE
);

CREATE TABLE User (
    EmployeeID VARCHAR(7) PRIMARY KEY NOT NULL,
    FirstName VARCHAR(255) NOT NULL,
    LastName VARCHAR(255) NOT NULL,
    LastFourSSN INT NOT NULL,
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
    FOREIGN KEY (EmployeeID) REFERENCES User(EmployeeID)
);

