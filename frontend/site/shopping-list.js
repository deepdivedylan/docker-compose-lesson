$(document).ready(() => {
	fetch("/api").then(reply => reply.json()).then(result => {
		console.log(result);
		let output = "";
		if(result.status !== 200) {
			output = "<div class=\"alert alert-danger\"><strong>Oh snap!</strong> " + result.message + "</div>";
		} else if(result.data.length === 0) {
			output = "<div class=\"alert alert-info\"><strong>Awww!</strong> The shopping list is empty.</div>";
		} else {
			result.data.map(shoppingList => {
				output = output + "<div class=\"card mb-3\">" +
					"<div class=\"card-body\">" +
					"<h5 class=\"card-title\">" + shoppingList.shoppingListItem + "</h5>" +
					"<p class=\"card-text\">Quantity: " + shoppingList.shoppingListQuantity + "</p>" +
					"</div>" +
					"</div>";
			});
		}
		$("#outputArea").html(output);
	});
});