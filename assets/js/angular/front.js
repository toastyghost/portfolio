var app = angular.module('portfolio', ['firebase']),
	fb = 'https://jdcportfolio.firebasio.com/';

app.controller('ClientsController', function($scope, $firebase) {
	$scope.clients = $firebase(new Firebase(fb + 'clients'));
});

app.controller('WorkController', function($scope, $firebase) {
	$scope.work = $firebase(new Firebase(fb + 'work'));
});

app.controller('ExperienceController', function($scope, $firebase) {
	$scope.design = $firebase(new Firebase(fb + 'experience'));
});

app.controller('EducationController', function($scope, $firebase) {
	$scope.design = $firebase(new Firebase(fb + 'education'));
});

app.controller('TestimonialsController', function($scope, $firebase) {
	$scope.design = $firebase(new Firebase(fb + 'testimonials'));
});