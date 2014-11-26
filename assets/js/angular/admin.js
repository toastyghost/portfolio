var app = angular.module('portfolio', ['firebase']),
	fb = 'https://jdcportfolio.firebaseio.com/',
	deletePrompt = 'Are you sure?';

app.controller('ClientsAdminController', function($scope, $firebase) {
	
	$scope.clients = $firebase(new Firebase(fb + 'clients'));
	
	$scope.addClient = function() {
		if ($scope.newclient.name != '' && $scope.newclient.logo != '' && $scope.newclient.type != '' && $scope.newclient.desc != '') {
			$scope.clients.$add($scope.newclient);
			$scope.newclient = null;
		}
	}
	
	$scope.editClient = function(id) {
		// TODO: implement inline editing
	}
	
	$scope.deleteClient = function(id) {
		if (confirm(deletePrompt)) {
			$firebase(new Firebase(fb + 'clients/' + id)).$remove();
		}
	}
});

app.controller('WorkAdminController', function($scope, $firebase) {
	
	$scope.work = $firebase(new Firebase(fb + 'work'));
	
	$scope.addWork = function() {
		if ($scope.newwork.title != '' && $scope.newwork.client != '' && $scope.newwork.description != '') {
			$scope.work.$add($scope.newwork);
			$scope.newwork = null;
		}
	}
	
	$scope.editWork = function(id) {
		// TODO: implement inline editing
	}
	
	$scope.deleteWork = function(id) {
		if (confirm(deletePrompt)) {
			$firebase(new Firebase(fb + 'Work/' + id)).$remove();
		}
	}
});

app.controller('ExperienceAdminController', function($scope, $firebase) {
	
	$scope.experience = $firebase(new Firebase(fb + 'experience'));
	$scope.parents = {};
	
	$scope.addExperience = function() {
		if ($scope.newexp.title != '' && $scope.newexp.employer != '' && $scope.newexp.start != '' && $scope.newexp.description != '') {
			$scope.experience.$add($scope.newexp);
			$scope.newexp = null;
		}
	}
	
	$scope.editExperience = function(id) {
		// TODO: implement inline editing
	}
	
	$scope.deleteExperience = function(id) {
		if (confirm(deletePrompt)) {
			$firebase(new Firebase(fb + 'experience/' + id)).$remove();
		}
	}
});

app.controller('EducationAdminController', function($scope, $firebase) {
	
	$scope.education = $firebase(new Firebase(fb + 'education'));
	
	$scope.addEducation = function() {
		if ($scope.newedu.school != '' && $scope.newedu.program != '' && $scope.newedu.start != '') {
			$scope.education.$add($scope.newedu);
			$scope.newedu = null;
		}
	}
	
	$scope.editEducation = function(id) {
		// TODO: implement inline editing
	}
	
	$scope.deleteEducation = function(id) {
		if (confirm(deletePrompt)) {
			$firebase(new Firebase(fb + 'education/' + id)).$remove();
		}
	}
});

app.controller('TestimonialsAdminController', function($scope, $firebase) {
	
	$scope.testimonials = $firebase(new Firebase(fb + 'testimonials'));
	
	$scope.addTestimonial = function() {
		if ($scope.newtestimonial.text != '' && $scope.newtestimonial.name != '') {
			$scope.testimonials.$add($scope.newtestimonial);
			$scope.newtestimonial = null;
		}
	}
	
	$scope.editTestimonial = function(id) {
		// TODO: implement inline editing
	}
	
	$scope.deleteTestimonial = function(id) {
		if (confirm(deletePrompt)) {
			$firebase(new Firebase(fb + 'testimonials/' + id)).$remove();
		}
	}
});