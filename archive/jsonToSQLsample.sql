CREATE TABLE assignments (assignment_id INTEGER PRIMARY KEY AUTOINCREMENT, assigned_by TEXT, assigned_to TEXT, json TEXT);
CREATE TABLE questionsets (questionset_id INTEGER PRIMARY KEY AUTOINCREMENT, created_by TEXT, json TEXT);

INSERT INTO assignments (assigned_by, assigned_to, json) VALUES (
'thomas.j.oneill@gmail.com','tjoeone@hotmail.com',
'{		
	"assignment_id" : 1,
	"questionset_id" : 1,
	"questionset_name" : "Breast Imaging 1",
	"created_by" : "thomas.j.oneill@gmail.com",
	"created_datetime" : "201303021705",
	"assigned_by" : "tjoeone@hotmail.com",
	"assigned_to" : "thomas.j.oneill@gmail.com",
	"questions" : [1, 2],
	"responses" : [4, 1],
	"keywords" : "breast,mammography,oncology",
	"difficulty" : "low"
	"assigned_datetime" : "201303021821",
	"started_datetime": "",
	"status" : 2
}');

INSERT INTO questionsets (created_by, json) VALUES (
'thomas.j.oneill@gmail.com',
'{		
	"questionset_id" : 1,
	"questionset_name" : "Breast Imaging 1",
	"created_by" : "thomas.j.oneill@gmail.com",
	"created_datetime" : "201303021705",
	"questions" : [1, 2],
	"keywords" : "breast,mammography,oncology",
	"difficulty" : "low"
}');

INSERT INTO questions (json) VALUES (
'{	
	"question_id" : 1,
	"created_by" : "thomas.j.oneill@gmail.com",
	"created_datetime" : "201303021700",
	"images" : [
		{
			"url" : "images\23r239802309824352q3085.jpg",
			"caption" : "Craniocaudal mammogram of the right breast"
		},
		{
			"url" : "images\0u77ywer87yewr34tasdg.jpg",
			"caption" : "Mediolateral oblique mammogram of the right breast"
		}
	],
	"multiple_choices" : [
		{
			"choice" : 1,
			"text" : "Tubular carcinoma",
			"explanation" : "",
			"correct" : true
		},
		{
			"choice" : 2,
			"text" : "Papillary carcinoma",
			"explanation" : "",
			"correct" : false
		},
		{
			"choice" : 3,
			"text" : "Triple negative invasive ductal carcinoma",
			"explanation" : "",
			"correct" : false
		},
		{
			"choice" : 4,
			"text" : "Medullary carcinoma",
			"explanation" : "",
			"correct" : false
		}
		
	],
	"keywords" : "breast,mammography,oncology",
	"difficulty" : "low"
}');
