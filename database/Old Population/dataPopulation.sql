USE 'DBGUI';

INSERT INTO Users(user_ID, active, dateCreated, fname, lname, email) VALUES

	(013, TRUE, '2012-03-13', "Dwayne The Rock", "Johnson", "rock@smu.edu"),
	(001, TRUE, '2012-04-14', "Macho Man Randy", "Savage", "savage@smu.edu"),
	(002, TRUE, '2013-05-15', "Hulk", "Hogan", "hulk@smu.edu"),
	(003, TRUE, '2013-06-16', "George", "Harrison", "george@smu.edu"),
	(004, TRUE, '2013-07-17', "Paul", "McCartney", "paul@smu.edu"),
	(005, TRUE, '2013-08-18', "Ringo", "Starr", "ringo@smu.edu"),
	(006, TRUE, '2013-09-19', "Barack", "Obama", "obama@smu.edu"),
	(007, TRUE, '2014-10-20', "Dubya", "Bush", "dubya@smu.edu"),
	(008, TRUE, '2013-11-21', "Willy", "Clinton", "bill@smu.edu"),
	(009, TRUE, '2012-12-22', "John", "Dorian", "dorian@smu.edu"),
	(010, TRUE, '2011-01-23', "Elliot", "Reid", "ET@smu.edu"),
	(011, TRUE, '2012-02-24', "Christopher", "Turkleton", "turk@smu.edu"),
	(012, TRUE, '2013-03-25', "Crazy", "Hooch", "hooch@smu.edu");
	
INSERT INTO Admin(user_ID) VALUES

	(006),
	(007),
	(008);

INSERT INTO Institution(inst_ID, name) VALUES

	(001, "Dedman"),
	(002, "Cox"),
	(003, "Meadows"),
	(004, "Simmons"),
	(005, "Lyle");

INSERT INTO Department(dept_ID, inst_ID, name) VALUES

	(013, 005, "CSE"),
	(020, 005, "EE"),
	(031, 005, "ME"),
	(028, 005, "EMIS"),
	(027, 002, "MBA");
	
INSERT INTO Student(user_ID, inst_ID, dept_ID, resume, graduateStudent, loginCount) VALUES

	(003, 005, 013, "dont got one", FALSE, 3),
	(004, 005, 020, "https:lol.com", FALSE, 2),
	(005, 005, 031, "yes", FALSE, 5),
	(009, 005, 028, "holy dookies", FALSE, 6),
	(010, 005, 031, "UUUUUHHHH", FALSE, 4),
	(011, 005, 031, "no", TRUE, 1),
	(012, 005, 027, "hue", TRUE, 2);

INSERT INTO Faculty(user_ID, inst_ID, dept_ID, loginCount) VALUES

	(013, 005, 013, 3),
	(001, 005, 020, 2),
	(002, 005, 027, 4);

INSERT INTO ResearchOp(researchOp_ID, user_ID, inst_ID, dept_ID, active, dateCreated, name, description, startDate, numPositions, paid, workStudy, acceptsUndergrad, acceptsGrad) VALUES

	(100, 013, 005, 013, TRUE, '2014-11-22', "Virtual Girlfriend Creation", "Create a girlfriend so you do not have to talk to real people!", '2015-01-15', 12, true, false, true, false),
	(101, 002, 005, 020, TRUE, '2014-11-23', "Business in America", "You live in America. Do the business OUR way.", '2015-03-13', 5, true,  false, false, true),
	(102, 001, 005, 031, TRUE, '2014-11-24', "Future of Art", "Art did not used to suck, maybe it will not suck in the future?", '2015-06-02', 1, false,  false, false, true),
	(103, 001, 005, 028, TRUE, '2014-11-25', "Dissection of Cow Stomach", "We made sure to kill the cow right after it ate. DIG IN!", '2015-10-30', 5, false, true, false, false),
	(104, 002, 005, 027, TRUE, '2014-11-26', "Court Behavior Analysis", "Psychopaths act weird in courtroom situations. Let us watch what they do.", '2015-12-12', 3, true, true, true, true);	
	
INSERT INTO Applicants(researchOp_ID, user_ID, status, dateSubmitted) VALUES
	(102, 009, "no?", '2015-12-14 00:00:00'),
	(103, 010, "yes please.", '2015-12-15 05:24:23'),
	(104, 011, "MMM WATCHU SAAAAAAAAY", '2015-12-13 06:21:30');

INSERT INTO Password(user_ID, password) VALUES
	(013, "ImCookingNoodles"),
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
