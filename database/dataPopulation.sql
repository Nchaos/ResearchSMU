USE 'DBGUI';

INSERT INTO Institution(inst_ID, name) VALUES

	(999, "SMU"),
	(998, "TCU");

INSERT INTO Users(user_ID, inst_ID, fname, lname) VALUES

	(000, 999, "Dwayne 'The Rock'", "Johnson"),
	(001, 999, "Macho Man Randy", "Savage"),
	(002, 999, "Hulk", "Hogan"),
	(003, 999, "George", "Harrison"),
	(004, 999, "Paul", "McCartney"),
	(005, 999, "Ringo", "Starr"),
	(006, 999, "Barack", "Obama"),
	(007, 999, "Dubya", "Bush"),
	(008, 998, "Willy", "Clinton"),
	(009, 999, "John", "Dorian"),
	(010, 999, "Elliot", "Reid"),
	(011, 999, "Christopher", "Turkleton"),
	(012, 999, "Crazy", "Hooch");

INSERT INTO Admin(admin_ID, user_ID) VALUES

	(200, 006),
	(201, 007),
	(202, 008);

INSERT INTO Guest(user_ID, Guestcol) VALUES

	(003, "George"),
	(004, "Paul"),
	(005, "Ringo");

INSERT INTO General(gen_ID, user_ID, loginCount, inst_ID, major, resume, graduate) VALUES

	(500, 009, 6, 999, "Biology", "yes plz", true),
	(501, 010, 50, 999, "Chemistry", "amazing", true),
	(502, 011, 3, 999, "Medical", "its all here baby", false),
	(503, 012, 1, 999, "Crazyology", "yo", false);

INSERT INTO Department(dept_ID, inst_ID, name, rop_ID) VALUES

	(600, 999, "Lyle", 100),
	(601, 999, "Cox", 101),
	(602, 999, "Meadows", 102),
	(603, 999, "Dedman Science", 103),
	(604, 999, "Dedman Law", 104);

INSERT INTO Faculty(faculty_ID, user_ID, inst_ID, dept_ID, loginCount) VALUES

	(700, 000, 999, 600, 3),
	(701, 001, 999, 601, 2),
	(702, 002, 999, 604, 4);

INSERT INTO ROP(rop_ID, faculty_ID, inst_ID, name, dateCreated, dateFinished, num_Positions, applicant_Count) VALUES

	(100, 700, 999, "Virtual Girlfriend Creation", "01/25/2015", "02/15/2015", 12, 0),
	(101, 701, 999, "Business in America", "03/13/2015", "03/18/2015", 5, 0),
	(102, 702, 999, "Future of Art", "06/02/2015", "07/01/2015", 1, 1),
	(103, 700, 999, "Disection of Cow Stomach", "10/30/2015", "10/30/2015", 5, 1),
	(104, 702, 999, "Court Behavior Analysis", "12/12/2015", "12/25/2015", 3, 1);	
	
INSERT INTO Applicants(app_ID, rop_ID, gen_ID) VALUES
	(800, 102, 500),
	(801, 103, 501),
	(802, 104, 502);

INSERT INTO Password(user_ID, password) VALUES
	(000, "ImCookingNoodles"),
	(001, "CreamOfTheCrop"),
	(002, "OhYeahBrother"),
	(003, "password1"),
	(004, "password2"),
	(005, "password3"),
	(006, "MyCountry"),
	(007, "NucularWar"),
	(008, "WhoIsMonica"),
	(009, "EEEEEEEAGLE"),
	(010, "Bajingo"),
	(011, "SurgeryR00lz"),
	(012, "CRaaaAaAAzy");


		