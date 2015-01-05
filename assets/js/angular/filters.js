app.filter('isTopLevel', function() {
	return function(items) {
		var filtered = [],
			i = 0;

		while (i < items.length) {
			if (items[i].parent == '') {
				filtered.push(items[i]);
			}
			++i;
		}

		return filtered;
	}
})