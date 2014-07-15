<!doctype html>
<html>
<head>
	<title>Admin Area</title>

	<script src="https://cdn.firebase.com/js/client/1.0.17/firebase.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>
	<script src="https://cdn.firebase.com/libs/angularfire/0.7.1/angularfire.min.js"></script>
	<script src="assets/js/angular/app.js"></script>
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
		
		<label for="client-name">Name</label>
		<input type="text" name="client-name" ng-model="newclient.name">
		
		<label for="client-logo">Logo</label>
		<input type="file" name="client-logo" ng-model="newclient.logo">
		
		<label for="client-type">Type</label>
		<input type="text" name="client-type" ng-model="newclient.type">
		
		<label for="client-description">Description</label>
		<textarea name="client-description" ng-model="newclient.description"></textarea>
		
		<button ng-click="addClient()">Add</button>
	</div>

	<div id="development" ng-controller="DevelopmentAdminController">
		<h2>Development</h2>
		
		<label for="development-title">Title</label>
		<input type="text" name="development-title" ng-model="newdev.title">
		
		<label for="development-type">Work Type</label>
		<input type="text" name="development-type" ng-model="newdev.type">
		
		<label for="development-client">Client</label>
		<input type="text" name="development-client" ng-model="newdev.client">
		
		<label for="development-description">Description</label>
		<textarea name="development-description" ng-model="newdev.description"></textarea>
		
		<label for="development-primary-image">Primary Image</label>
		<input type="file" name="development-primary-image" ng-model="newdev.primaryImage">
		
		<label for="development-secondary-image">Secondary Image</label>
		<input type="file" name="development-secondary-image" ng-model="newdev.secondaryImage">
		
		<label for="development-tertiary-image">Tertiary Image</label>
		<input type="file" name="development-tertiary-image" ng-model="newdev.tertiaryImage">
		
		<button ng-click="addDevelopment()">Add</button>
	</div>

	<div id="design">
		Coming Soon&hellip;
	</div>

	<div id="resume">
		<h2>Resume</h2>
		<div id="experience" ng-controller="ExperienceAdminController">
			<h3>Experience</h3>
			
			<label for="resume-experience-title">Title</label>
			<input type="text" name="resume-experience-title" ng-model="newexp.title">
			
			<label for="resume-experience-employer">Employer</label>
			<input type="text" name="resume-experience-employer" ng-model="newexp.employer">
			
			<label for="resume-experience-period-start">Start</label>
			<input type="date" name="resume-experience-period-start" ng-model="newexp.start">
			
			<label for="resume-experience-period-end">End</label>
			<input type="date" name="resume-experience-period-end" ng-model="newexp.end">
			
			<label for="resume-experience-description">Description</label>
			<textarea name="resume-experience-description" ng-model="newexp.description"></textarea>
			
			<button ng-click="addExperience()">Add</button>
		</div>
		
		<div id="education" ng-controller="EducationAdminController">
			<h3>Education</h3>
			
			<label for="resume-education-school">School</label>
			<input type="text" name="resume-education-school" ng-model="newedu.school">
			
			<label for="resume-education-program">Program</label>
			<input type="text" name="resume-education-program" ng-model="newedu.program">
			
			<label for="resume-education-period-start">Start</label>
			<input type="date" name="resume-education-period-start" ng-model="newedu.start">
			
			<label for="resume-education-period-end">End</label>
			<input type="date" name="resume-education-period-end" ng-model="newedu.end">
			
			<label for="resume-education-arrangement">Arrangement</label>
			<input type="text" name="resume-education-arrangement" ng-model="newedu.arrangement">
			
			<button ng-click="addEducation()">Add</button>
		</div>
	</div>
	
	<div id="testimonials" ng-controller="TestimonialsAdminController">
		<h2>Testimonials</h2>
		
		<label for="testimonial-excerpt">Excerpt</label>
		<input type="text" name="testimonial-excerpt" ng-model="newtestimonial.excerpt">
		
		<label for="testimonial-text">Text</label>
		<textarea name="testimonial-text" ng-model="newtestimonial.text"></textarea>
		
		<label for="testimonial-name">Name</label>
		<input type="text" name="testimonial-name" ng-model="newtestimonial.name">
		
		<label for="testimonial-title">Title</label>
		<input type="text" name="testimonial-title" ng-model="newtestimonial.title">
		
		<label for="testimonial-organization">Organization</label>
		<input type="text" name="testimonial-organization" ng-model="newtestimonial.organization">
		
		<button ng-click="addTestimonial()">Add</button>
	</div>

</div>

</body>
</html>