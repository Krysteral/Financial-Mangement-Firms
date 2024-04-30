-- Option 2: Kien and Visho

-- Query 1 (Visho): calculates the average amount of transactions for each category.
SELECT IT.TypeName, 
ROUND(AVG(I.AmountInvested), 1) AS AverageInvested
FROM Investments AS I
JOIN InvestmentTypes AS IT ON I.InvestmentTypeID = IT.InvestmentTypeID
GROUP BY IT.TypeName
ORDER BY IT.TypeName;


-- Query 2 (Kien): Finding Users that have a Checking account and their balance:
SELECT CONCAT(Users.FName, ' ', Users.LName) AS User, Accounts.Balance AS Balance
FROM Users NATURAL JOIN Accounts WHERE AccountTypeID = (SELECT AccountTypeID FROM AccountTypes WHERE TypeName='Checking');

-- Kien's query 3: for each User's 'FName LName' (use concat to list together),
-- list all transaction descriptions on one line. List users' 'FName LName' once 
-- with many transaction.description - use group concat.
SELECT CONCAT(Users.FName, ' ', Users.LName) AS User,
GROUP_CONCAT(Transactions.Description SEPARATOR ', ') AS TransactionDes
FROM Users NATURAL JOIN Accounts NATURAL JOIN Transactions 
GROUP BY Users.UserID ORDER BY Users.LName, Users.FName;

-- Visho's query 3: for each Category.Name list all 'Fname Lname' (use concat to list both names).
-- List one Transaction.description with many Users 'Fname Lname'
SELECT c.Name AS CategoryName,
GROUP_CONCAT(DISTINCT t.Description ORDER BY t.Description SEPARATOR ', ') AS Descriptions,
GROUP_CONCAT(DISTINCT CONCAT(u.FName, ' ', u.LName) ORDER BY u.FName, u.LName SEPARATOR ', ') AS Users
FROM Categories c
NATURAL JOIN Transactions t 
NATURAL JOIN Accounts a 
NATURAL JOIN Users u
GROUP BY c.CategoryID
ORDER BY c.Name;
