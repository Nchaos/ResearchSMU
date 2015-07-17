INSERT INTO Institution(inst_ID, name) VALUES

	(001, "Dedman"),
	(002, "Cox"),
	(003, "Meadows"),
	(004, "Simmons"),
	(005, "Lyle");

INSERT INTO Department(dept_ID, inst_ID, name) VALUES

	(003, 001, "Anthropology"),
	(008, 001, "Biological Sciences"),
	(010, 001, "Chemistry"),
	(018, 001, "Earth Sciences"),
	(019, 001, "Economics"),
	(021, 001, "English"),
	(025, 001, "History"),
	(030, 001, "Math"),
	(033, 001, "Philosophy"),
	(034, 001, "Physics"),
	(035, 001, "Political Science"),
	(036, 001, "Psychology"),
	(038, 001, "Religious Sciences"),
	(041, 001, "Sociology"),
	(042, 001, "Statistical Sciences"),
	(046, 001, "World Languages"),
	(011, 005, "Civil & Environmental Engineering"),
	(013, 005, "Computer Science & Engineering"),
	(020, 005, "Electrical Engineering"),
	(028, 005, "Management Sciences"),
	(031, 005, "Mechanical Engineering"),
	(001, 002, "Accounting"),
	(023, 002, "Finance"),
	(029, 002, "Marketing"),
	(027, 002, "Management"),
	(037, 002, "Real Estate"),
	(039, 002, "Risk Management"),
	(002, 003, "Advertising"),
	(005, 003, "Art"),
	(006, 003, "Art History"),
	(007, 003, "Art Management"),
	(012, 003, "Communication"),
	(015, 003, "Creative Computing"),
	(016, 003, "Dance"),
	(022, 003, "Film & Media Arts"),
	(026, 003, "Journalism"),
	(032, 003, "Music"),
	(044, 003, "Theatre"),
	(004, 004, "Applied Physiology"),
	(014, 004, "Counseling"),
	(017, 004, "Dispute Resolution"),
	(024, 004, "Higher Education"),
	(040, 004, "Sports Management"),
	(043, 004, "Teacher Education"),
	(045, 004, "Wellness");


INSERT INTO Users(user_ID, active, dateCreated, fname, lname, email, userType) VALUES

	(001, TRUE, '2015-07-12', "Nicholas", "Chao", "nhchao@smu.edu", "Admin"),
	(002, TRUE, '2015-07-12', "Mary", "McCreary", "mmcreary@smu.edu", "Admin"),
	(003, TRUE, '2015-07-12', "Mark", "Fontenot", "mfonten@lyle.smu.edu", "Faculty"); -- move this seimcolon to the last user entry


INSERT INTO Faculty(user_ID, inst_ID, dept_ID, loginCount) VALUES

	(001, 005, 013, 1),
	(002, 005, 031, 1),
	(003, 005, 013, 1); -- move this seimcolon to the last user entry

INSERT INTO Admin(user_ID) VALUES

	(001),
	(002);


