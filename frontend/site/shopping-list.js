function reloadShoppingList() {
	fetch("/api").then(reply => reply.json()).then(result => {
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
}

$(document).ready(() => {
	reloadShoppingList();

	$("#shoppingListForm").submit(event => {
		let shoppingList = {shoppingListItem: $("#shoppingListItem").val(), shoppingListQuantity: parseInt($("#shoppingListQuantity").val())};
		fetch("/api", {
			method: "POST",
			body: JSON.stringify(shoppingList),
			headers: {
				"Content-type": "application/json"
			}
		}).then(reply => reply.json()).then(result => {
			let output = "";
			if(result.status !== 200) {
				output = "<div class=\"alert alert-danger\"><strong>Oh snap!</strong> " + result.message + "</div>";
			} else {
				output = "<div class=\"alert alert-success\"><strong>Well done!</strong> " + result.message + "</div>";
				$("#shoppingListForm").trigger("reset");
				reloadShoppingList();
			}
			$("#messageArea").html(output);
		});
		event.preventDefault();
	});
});