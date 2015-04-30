USE 'DBGUI';

INSERT INTO Users( active, dateCreated, fname, lname, email) VALUES

	( TRUE, '2012-04-14', "Macho Man Randy", "Savage", "savage@smu.edu"),
	( TRUE, '2013-05-15', "Hulk", "Hogan", "hulk@smu.edu"),
	( TRUE, '2013-06-16', "George", "Harrison", "george@smu.edu"),
	( TRUE, '2013-07-17', "Paul", "McCartney", "paul@smu.edu"),
	( TRUE, '2013-08-18', "Ringo", "Starr", "ringo@smu.edu"),
	( TRUE, '2013-09-19', "Barack", "Obama", "obama@smu.edu"),
	( TRUE, '2014-10-20', "Dubya", "Bush", "dubya@smu.edu"),
	( TRUE, '2013-11-21', "Willy", "Clinton", "bill@smu.edu"),
	( TRUE, '2012-12-22', "John", "Dorian", "dorian@smu.edu"),
	( TRUE, '2011-01-23', "Elliot", "Reid", "ET@smu.edu"),
	( TRUE, '2012-02-24', "Christopher", "Turkleton", "turk@smu.edu"),
	( TRUE, '2013-03-25', "Crazy", "Hooch", "hooch@smu.edu");
	
INSERT INTO Admin(user_ID) VALUES

	(006),
	(007),
	(008);

INSERT INTO Institution(inst_ID, name) VALUES

	(999, "Lyle"),
	(998, "Cox");

INSERT INTO Department(dept_ID, inst_ID, name) VALUES

	(600, 999, "CSE"),
	(601, 999, "EE"),
	(602, 999, "ME"),
	(603, 999, "EMIS"),
	(604, 998, "MBA");
	
INSERT INTO Student(user_ID, inst_ID, dept_ID, resume, graduateStudent, loginCount) VALUES

	(003, 999, 600, "dont got one", FALSE, 3),
	(004, 999, 601, "https:lol.com", FALSE, 2),
	(005, 999, 602, "yes", FALSE, 5),
	(009, 999, 603, "holy dookies", FALSE, 6),
	(010, 999, 602, "UUUUUHHHH", FALSE, 4),
	(011, 999, 602, "no", TRUE, 1),
	(012, 999, 604, "hue", TRUE, 2);

INSERT INTO Faculty(user_ID, inst_ID, dept_ID, loginCount) VALUES

	(001, 999, 601, 2),
	(002, 999, 604, 4);

INSERT INTO ResearchOp(researchOp_ID, user_ID, inst_ID, dept_ID, active, dateCreated, name, description, startDate, numPositions, paid, workStudy, acceptsUndergrad, acceptsGrad) VALUES

	(100, 000, 999, 600, TRUE, '2014-11-22', "Virtual Girlfriend Creation", "Create a girlfriend so you don't have to talk to real people!", '2015-01-15', 12, true, false, true, false),
	(101, 001, 999, 601, TRUE, '2014-11-23', "Business in America", "You live in America. Do the business OUR way.", '2015-03-13', 5, true,  false, false, true),
	(102, 002, 999, 602, TRUE, '2014-11-24', "Future of Art", "Art didn't used to suck, maybe it won't suck in the future?", '2015-06-02', 1, false,  false, false, true),
	(103, 000, 999, 603, TRUE, '2014-11-25', "Dissection of Cow Stomach", "We made sure to kill the cow right after it ate. DIG IN!", '2015-10-30', 5, false, true, false, false),
	(104, 002, 999, 604, TRUE, '2014-11-26', "Court Behavior Analysis", "Psychopaths act weird in courtroom situations. Let's watch what they do.", '2015-12-12', 3, true, true, true, true);	
	
INSERT INTO Applicants(researchOp_ID, user_ID, status, dateSubmitted) VALUES
	(102, 009, "no?", '2015-12-14 00:00:00'),
	(103, 010, "yes please.", '2015-12-15 05:24:23'),
	(104, 011, "MMM WATCHU SAAAAAAAAY", '2015-12-13 06:21:30');

INSERT INTO Password(user_ID, password) VALUES
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