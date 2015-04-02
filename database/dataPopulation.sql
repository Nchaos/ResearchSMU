USE 'DBGUI';

INSERT INTO Institution(inst_ID, name) VALUES

	(999, "SMU"),
	(998, "TCU");

INSERT INTO Users(user_ID, fname, lname, email) VALUES

	(000, "Dwayne 'The Rock'", "Johnson", "rock@smu.edu"),
	(001, "Macho Man Randy", "Savage", "savage@smu.edu"),
	(002, "Hulk", "Hogan", "hulk@smu.edu"),
	(003, "George", "Harrison", "george@smu.edu"),
	(004, "Paul", "McCartney", "paul@smu.edu"),
	(005, "Ringo", "Starr", "ringo@smu.edu"),
	(006, "Barack", "Obama", "obama@smu.edu"),
	(007, "Dubya", "Bush", "dubya@smu.edu"),
	(008, "Willy", "Clinton", "bill@smu.edu"),
	(009, "John", "Dorian", "dorian@smu.edu"),
	(010, "Elliot", "Reid", "ET@smu.edu"),
	(011, "Christopher", "Turkleton", "turk@smu.edu"),
	(012, "Crazy", "Hooch", "hooch@smu.edu");

INSERT INTO Admin(user_ID) VALUES

	(006),
	(007),
	(008);

INSERT INTO Department(dept_ID, inst_ID, name) VALUES

	(600, 999, "Lyle"),
	(601, 999, "Cox"),
	(602, 999, "Meadows"),
	(603, 999, "Dedman Science"),
	(604, 999, "Dedman Law");

INSERT INTO Faculty(user_ID, inst_ID, dept_ID, loginCount) VALUES

	(000, 999, 600, 3),
	(001, 999, 601, 2),
	(002, 999, 604, 4);

INSERT INTO ResearchOP(researchOp_ID, faculty_ID, inst_ID, dept_ID, name, dateCreated, dateFinished, num_Positions, applicant_Count, paid, work_study, graduate, undergraduate) VALUES

	(100, 000, 999, 600, "Virtual Girlfriend Creation", "01/25/2015", "02/15/2015", 12, 0, true, false, true, false),
	(101, 001, 999, 601, "Business in America", "03/13/2015", "03/18/2015", 5, 0, true, false, false, true),
	(102, 002, 999, 602, "Future of Art", "06/02/2015", "07/01/2015", 1, 1, false, false, false, true),
	(103, 000, 999, 603, "Disection of Cow Stomach", "10/30/2015", "10/30/2015", 5, 1, false, true, false, false),
	(104, 002, 999, 604, "Court Behavior Analysis", "12/12/2015", "12/25/2015", 3, 1, true, true, true, false);	
	
INSERT INTO Applicants(researchOp_ID, user_ID) VALUES
	(102, 009),
	(103, 010),
	(104, 011);

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


		