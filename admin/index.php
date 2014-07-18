<!doctype html>
<html>
<head>
	<title>Admin Area</title>
	
	<link rel="stylesheet" href="../assets/css/admin.css">

	<script src="https://cdn.firebase.com/js/client/1.0.17/firebase.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>
	<script src="https://cdn.firebase.com/libs/angularfire/0.7.1/angularfire.min.js"></script>
	<script src="../assets/js/angular/app.js"></script>
</head>

<body>

<div id="main" ng-app="portfolio">
	
	<nav id="admin-nav">
		<a href="#clients">Clients</a>
		<a href="#development">Development</a>
		<a href="#design">Design</a>
		<a href="#resume">Resume</a>
		<a href="#testimonials">Testimonials</a>
	</nav>
	
	<div id="clients" ng-controller="ClientsAdminController">
		<h2>Clients</h2>
		
		<table class="entries-table">
			<tr>
				<th>Name</th>
				<th>Logo</th>
				<th>Type</th>
				<th colspan="2">Description</th>
			</tr>
			
			<tr ng-repeat="(id, client) in clients">
				<td>{{client.name}}</td>
				<td>{{client.logo}}</td>
				<td>{{client.type}}</td>
				<td>{{client.desc}}</td>
				<td>
					<button ng-click="editClient(id)">Edit</button>
					<button ng-click="deleteClient(id)">Delete</button>
				</td>
			</tr>
			
			<tr>
				<td><input type="text" name="client-name" ng-model="newclient.name"></td>
				<td><input type="file" name="client-logo" ng-model="newclient.logo"></td>
				<td><input type="text" name="client-type" ng-model="newclient.type"></td>
				<td><textarea name="client-desc" ng-model="newclient.desc"></textarea></td>
				<td><button ng-click="addClient()">Add</button></td>
			</tr>
		</table>
		
	</div>

	<div id="development" ng-controller="DevelopmentAdminController">
		<h2>Development</h2>
		
		<table class="entries-table">
			<tr>
				<th>Title</th>
				<th>Work Type</th>
				<th>Client</th>
				<th>Description</th>
				<th>Primary Image</th>
				<th>Secondary Image</th>
				<th colspan="2">Tertiary Image</th>
			</tr>
			
			<tr ng-repeat="(id, dev) in development">
				<td>{{dev.title}}</td>
				<td>{{dev.type}}</td>
				<td>{{dev.client}}</td>
				<td>{{dev.desc}}</td>
				<td>{{dev.primaryImage}}</td>
				<td>{{dev.secondaryImage}}</td>
				<td>{{dev.tertiaryImage}}</td>
				<td>
					<button ng-click="editDevelopment(id)">Edit</button>
					<button ng-click="deleteDevelopment(id)">Delete</button>
				</td>
			</tr>
			
			<tr>
				<td><input type="text" name="development-title" ng-model="newdev.title"></td>
				<td><input type="text" name="development-type" ng-model="newdev.type"></td>
				<td><input type="text" name="development-client" ng-model="newdev.client"></td>
				<td><textarea name="development-description" ng-model="newdev.desc"></textarea></td>
				<td><input type="file" name="development-primary-image" ng-model="newdev.primaryImage"></td>
				<td><input type="file" name="development-secondary-image" ng-model="newdev.secondaryImage"></td>
				<td><input type="file" name="development-tertiary-image" ng-model="newdev.tertiaryImage"></td>
				<td><button ng-click="addDevelopment()">Add</button></td>
			</tr>
		</table>
		
	</div>

	<div id="design">
		<!-- Coming soon... -->
	</div>

	<div id="resume">
		<h2>Resume</h2>
		<div id="experience" ng-controller="ExperienceAdminController">
			<h3>Experience</h3>
			
			<table class="entries-table">
				<tr>
					<th>Title</th>
					<th>Employer</th>
					<th>Start</th>
					<th>End</th>
					<th>Arrangement Type</th>
					<th colspan="2">Description</th>
				</tr>
				
				<tr ng-repeat="(id, exp) in experience">
					<td>{{exp.title}}</td>
					<td>{{exp.employer}}</td>
					<td>{{exp.start}}</td>
					<td>{{exp.end}}</td>
					<td>{{exp.arrange}}</td>
					<td>{{exp.desc}}</td>
					<td>
						<button ng-click="editExperience(id)">Edit</button>
						<button ng-click="deleteExperience(id)">Delete</button>
					</td>
				</tr>
				
				<tr>
					<td><input type="text" name="experience-title" ng-model="newexp.title"></td>
					<td><input type="text" name="experience-employer" ng-model="newexp.employer"></td>
					<td><input type="date" name="experience-period-start" ng-model="newexp.start"></td>
					<td><input type="date" name="experience-period-end" ng-model="newexp.end"></td>
					<td><input type="text" name="experience-arrangement" ng-model="newexp.arrange"></td>
					<td><textarea name="experience-description" ng-model="newexp.description"></textarea></td>
					<td><button ng-click="addExperience()">Add</button></td>
				</tr>
			</table>
			
			
		</div>
		
		<div id="education" ng-controller="EducationAdminController">
			<h3>Education</h3>
			
			<table class="entries-table">
				<tr>
					<th>School</th>
					<th>Program</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th colspan="2">Arrangement Type</th>
				</tr>
				
				<tr ng-repeat="(id, edu) in education">
					<td>{{edu.school}}</td>
					<td>{{edu.program}}</td>
					<td>{{edu.start}}</td>
					<td>{{edu.end}}</td>
					<td>{{edu.arrange}}</td>
					<td>
						<button ng-click="editEducation(id)">Edit</button>
						<button ng-click="deleteEducation(id)">Delete</button>
					</td>
				</tr>
				
				<tr>
					<td><input type="text" name="education-school" ng-model="newedu.school"></td>
					<td><input type="text" name="education-program" ng-model="newedu.program"></td>
					<td><input type="date" name="education-period-start" ng-model="newedu.start"></td>
					<td><input type="date" name="education-period-end" ng-model="newedu.end"></td>
					<td><input type="text" name="education-arrangement" ng-model="newedu.arrange"></td>
					<td><button ng-click="addEducation()">Add</button></td>
				</tr>
			</table>
		</div>
	</div>
	
	<div id="testimonials" ng-controller="TestimonialsAdminController">
		<h2>Testimonials</h2>
		
		<table class="entries-table">
			<tr>
				<th>Excerpt</th>
				<th>Text</th>
				<th>Name</th>
				<th>Title</th>
				<th colspan="2">Organization</th>
			</tr>
			
			<tr ng-repeat="(id, testimonial) in testimonials">
				<td>{{testimonial.excerpt}}</td>
				<td>{{testimonial.text}}</td>
				<td>{{testimonial.name}}</td>
				<td>{{testimonial.title}}</td>
				<td>{{testimonial.org}}</td>
				<td>
					<button ng-click="editTestimonial(id)">Edit</button>
					<button ng-click="deleteTestimonial(id)">Delete</button>
				</td>
			</tr>
			
			<tr>
				<td><input type="text" name="testimonial-excerpt" ng-model="newtestimonial.excerpt"></td>
				<td><textarea name="testimonial-text" ng-model="newtestimonial.text"></textarea></td>
				<td><input type="text" name="testimonial-name" ng-model="newtestimonial.name"></td>
				<td><input type="text" name="testimonial-title" ng-model="newtestimonial.title"></td>
				<td><input type="text" name="testimonial-organization" ng-model="newtestimonial.org"></td>
				<td><button ng-click="addTestimonial()">Add</button></td>
			</tr>
		</table>
		
	</div>

</div>

</body>
</html>