USE `DBGUI` ;

INSERT INTO Department(dept_ID, inst_ID, name, rop_ID) VALUES

	(000, 999, "Lyle", 123),

	(001, 999, "Cox", 124),

	(002, 999, "Meadows", 125),

	(003, 999, "Dedman Science", 126),

	(004, 999, "Dedman Law", 127);

INSERT INTO Faculty(faculty_ID, user_ID, inst_ID, dept_ID, fname, lname, loginCount) VALUES

	(000, 000, 999, 000, "Dwayne 'The Rock'", "Johnson", 3),
	(001, 001, 999, 001, "Macho Man Randy", "Savage", 2),
	(002, 002, 999, 004, "Hulk", "Hogan", 4);
INSERT INTO Institution(inst_ID, name) VALUES
 
	(999, "SMU"),
	(998, "TCU");

INSERT INTO ROP(rop_ID, faculty_ID, inst_ID, name, dateCreated, dateFinished, num_Positions) VALUES

	(123, 000, 999, "Virtual Girlfriend Creation", "01/25/2015", "02/15/2015", 12),
	(124, 001, 999, "Business in America", "03/13/2015", "03/18/2015", 5),
	(125, 002, 999, "Future of Art", "06/02/2015", "07/01/2015", 1),
	(126, 000, 999, "Disection of Cow Stomach", "10/30/2015", "10/30/2015", 5),
	(127, 002, 999, "Court Behavior Analysis", "12/12/2015", "12/25/2015", 3);	

INSERT INTO Guest(user_ID, Guestcol) VALUES

	(003, "George"),
	(004, "Steve"),
	(005, "John");
INSERT INTO Users(user_ID, inst_ID) VALUES
	(000, 999),
	(001, 999),
	(002, 999),
	(003, 999),
	(004, 999),
	(005, 999),
	(006, 999),
	(007, 999),
	(008, 998),
	(009, 999),
	(010, 999),
	(011, 999),
	(012, 999);
INSERT INTO Admin(admin_ID, user_ID, fname, lname) VALUES
	(00, 006, "Barack", "Obama"),
	(01, 007, "Dubya", "Bush"),
	(02, 008, "Willy", "Clinton");
INSERT INTO Password(user_ID, password) VALUES
	(000, "ImCookingNoodles"),
	(001, "CreamOfTheCrop"),
	(002, "OhYeahBrother"),
	(003, "password1"),
	(004, "password2"),
	(005, "password3"),
	(006, "MyCountry"),
	(007, "NucularWar"),
	(008, "WhoIsMonica");
INSERT INTO General(gen_ID, user_ID, fname, lname, loginCount, inst_ID, major, resume, graduate) VALUES
	(000, 009, "John", "Dorian", 6, 999, "Biology", "yes plz", true),
	(001, 010, "Elliot", "Reid", 50, 999, "Chemistry", "amazing", true),
	(002, 011, "Christopher", "Turkleton", 3, 999, "Medical", "its all here baby", false),
	(003, 012, "Crazy", "Hooch", 1, 999, "Crazyology", "yo", false);
INSERT INTO Applicants(rop_ID, gen_ID, count) VALUES
	(123, 009, 2),
	(124, 010, 5),
	(125, 011, 1);
		