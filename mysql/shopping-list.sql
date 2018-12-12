DROP TABLE IF EXISTS shoppingList;

CREATE TABLE shoppingList (
	shoppingListId BINARY(16) NOT NULL,
	shoppingListItem VARCHAR(64) NOT NULL,
	shoppingListQuantity TINYINT UNSIGNED NOT NULL,
	PRIMARY KEY(shoppingListId)
);