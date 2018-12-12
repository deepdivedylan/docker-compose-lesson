<?php
namespace Deepdivedylan\DockerComposeLesson;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Silly Shopping List Item Class
 *
 * This is a class that stores the absolute minimum required for a play shopping list
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @version 1.0.0
 **/
class ShoppingList implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for this Shopping List; this is the primary key
	 * @var Uuid $shoppingListId
	 **/
	private $shoppingListId;
	/**
	 * description of the item on the Shopping List
	 * @var string $shoppingListItem
	 **/
	private $shoppingListItem;
	/**
	 * quantity of the item on the Shopping List
	 * @var int $shoppingListQuantity
	 **/
	private $shoppingListQuantity;

	/**
	 * constructor for this Shopping List
	 *
	 * @param string|Uuid $newShoppingListId id of this Shopping List
	 * @param string $newShoppingListItem description of this Shopping List
	 * @param int $newShoppingListQuantity quantity of this Shopping List
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct($newShoppingListId, string $newShoppingListItem, int $newShoppingListQuantity) {
		try {
			$this->setShoppingListId($newShoppingListId);
			$this->setShoppingListItem($newShoppingListItem);
			$this->setShoppingListQuantity($newShoppingListQuantity);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			//determine what exception type was thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for this shopping list id
	 *
	 * @return Uuid value of shopping list id
	 **/
	public function getShoppingListId(): Uuid {
		return($this->shoppingListId);
	}

	/**
	 * accessor method for this shopping list item
	 *
	 * @return string value of shopping list item
	 **/
	public function getShoppingListItem(): string {
		return($this->shoppingListItem);
	}

	/**
	 * accessor method for this shopping list quantity
	 *
	 * @return int value of shopping list quantity
	 **/
	public function getShoppingListQuantity(): int {
		return($this->shoppingListQuantity);
	}
}