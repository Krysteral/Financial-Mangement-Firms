ALTER TABLE Accounts DROP FOREIGN KEY FK_User;
ALTER TABLE Accounts DROP FOREIGN KEY FK_Type;
ALTER TABLE Transactions DROP FOREIGN KEY FK_Account;
ALTER TABLE Transactions DROP FOREIGN KEY FK_Category;
ALTER TABLE Categories DROP FOREIGN KEY FK_TypeC;
ALTER TABLE Investments DROP FOREIGN KEY FK_UserI;
ALTER TABLE Investments DROP FOREIGN KEY FK_TypeI;
ALTER TABLE Budgets DROP FOREIGN KEY FK_UserB;
ALTER TABLE SavingsGoals DROP FOREIGN KEY FK_UserS;

-- Drop table
DROP TABLE IF EXISTS Users, Accounts, Transactions, 
Categories, Budgets, SavingsGoals, Investments, 
AccountTypes, InvestmentTypes, CategoryType;

-- Credentials tables: Users and Accounts
-- Create Users Table
CREATE TABLE Users (
    UserID INT,
    FName VARCHAR(255) NOT NULL,
    LName VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL,
    RegistrationDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(UserID)
);

-- Create Accounts Table
CREATE TABLE Accounts (
    AccountID INT,
    UserID INT NOT NULL,
    AccountTypeID INT NOT NULL,
    InstitutionName VARCHAR(255),
    Balance DECIMAL(15, 2) NOT NULL,
    Currency VARCHAR(3) NOT NULL,
    PRIMARY KEY(AccountID)
);

CREATE TABLE AccountTypes (
    AccountTypeID INT,
    TypeName VARCHAR(50) NOT NULL,
    PRIMARY KEY(AccountTypeID)
);

-- Cashflow tables: Transactions, Investments, Categories
-- Create Transactions Table
CREATE TABLE Transactions (
    TransactionID INT,
    AccountID INT NOT NULL,
    Date datetime,
    Amount DECIMAL(15, 2) NOT NULL,
    CategoryID INT,
    Description VARCHAR(255),
    PRIMARY KEY(TransactionID)
);

-- Create Categories Table
CREATE TABLE CategoryType (
    TypeID INT AUTO_INCREMENT,
    TypeName VARCHAR(255),
    PRIMARY KEY(TypeID)
);
CREATE TABLE Categories (
    CategoryID INT,
    Name VARCHAR(255), 
    TypeID INT NOT NULL,
    PRIMARY KEY(CategoryID)
);

-- CREATE TABLE TransactionCategories(
--     TransactionID INT,
--     CategoryID INT,
--     PRIMARY KEY (TransactionID, CategoryID)
-- )

-- Create Investments Table
CREATE TABLE Investments (
    InvestmentID INT,
    AccountID INT NOT NULL,
    AmountInvested DECIMAL(15, 2) NOT NULL,
    CurrentValue DECIMAL(15, 2) NOT NULL,
    DatePurchased DATE NOT NULL,
    InvestmentTypeID INT NOT NULL,
    PRIMARY KEY(InvestmentID)
);

CREATE TABLE InvestmentTypes (
    InvestmentTypeID INT,
    TypeName VARCHAR(50) NOT NULL,
    PRIMARY KEY(InvestmentTypeID)
);


-- Financial Goals tables: Budgets, SavingsGoals
-- Create Budgets Table
CREATE TABLE Budgets (
    BudgetID INT,
    UserID INT NOT NULL,
    Amount DECIMAL(15, 2) NOT NULL,
    StartDate DATE NOT NULL,
    EndDate DATE NOT NULL,
    PRIMARY KEY(BudgetID)
);

-- Create Savings Goals Table
CREATE TABLE SavingsGoals (
    GoalID INT,
    UserID INT NOT NULL,
    Name VARCHAR(255) NOT NULL,
    TargetAmount DECIMAL(15, 2) NOT NULL,
    CurrentAmount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    TargetDate DATE NOT NULL,
    PRIMARY KEY(GoalID)
);

ALTER TABLE Accounts ADD CONSTRAINT FK_User FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE;
ALTER TABLE Accounts ADD CONSTRAINT FK_Type FOREIGN KEY (AccountTypeID) REFERENCES AccountTypes(AccountTypeID) ON DELETE CASCADE;
ALTER TABLE Categories ADD CONSTRAINT FK_TypeC FOREIGN KEY (TypeID) REFERENCES CategoryType(TypeID) ON DELETE CASCADE;
ALTER TABLE Transactions ADD CONSTRAINT FK_Account FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID) ON DELETE CASCADE;
ALTER TABLE Transactions ADD CONSTRAINT FK_Category FOREIGN KEY (CategoryID) REFERENCES Categories(CategoryID) ON DELETE CASCADE;
ALTER TABLE Investments ADD CONSTRAINT FK_UserI FOREIGN KEY (AccountID) REFERENCES Accounts(AccountID) ON DELETE CASCADE;
ALTER TABLE Investments ADD CONSTRAINT FK_TypeI FOREIGN KEY (InvestmentTypeID) REFERENCES InvestmentTypes(InvestmentTypeID) ON DELETE CASCADE;
ALTER TABLE Budgets ADD CONSTRAINT FK_UserB FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE;
ALTER TABLE SavingsGoals ADD CONSTRAINT FK_UserS FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE; 
-- AlTER TABLE TransactionCategories ADD CONSTRAINT FK_TC FOREIGN KEY (TransactionID) REFERENCES Transactions(TransactionID) ON DELETE CASCADE;
-- ALTER TABLE TransactionCategories ADD CONSTRAINT FK_TC1 FOREIGN KEY (CategoryID) REFERENCES Categories(CategoryID) ON DELETE CASCADE;