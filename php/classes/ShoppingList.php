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
	 * mutator method for shopping list id
	 *
	 * @param string|Uuid $newShoppingListId new value of shopping list id
	 * @throws \RangeException if $newShoppingListId is not positive
	 * @throws \TypeError if $newShoppingListId is not a uuid or string
	 */
	public function setShoppingListId($newShoppingListId): void {
		try {
			$uuid = self::validateUuid($newShoppingListId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->shoppingListId = $uuid;
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
	 * mutator method for shopping list item
	 *
	 * @param string $newShoppingListItem new value of shopping list item
	 * @throws \InvalidArgumentException if $newShoppingListItem is not a string or insecure
	 * @throws \RangeException if $newShoppingListItem is > 64 characters
	 * @throws \TypeError if $newShoppingListItem is not a string
	 */
	public function setShoppingListItem(string $newShoppingListItem): void {
		// verify the shopping list item is secure
		$newShoppingListItem = trim($newShoppingListItem);
		$newShoppingListItem = filter_var($newShoppingListItem, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newShoppingListItem) === true) {
			throw(new \InvalidArgumentException("shopping list item is empty or insecure"));
		}

		// verify the shopping list item will fit in the database
		if(strlen($newShoppingListItem) > 64) {
			throw(new \RangeException("shopping list item is too large"));
		}
		$this->shoppingListItem = $newShoppingListItem;
	}

	/**
	 * accessor method for this shopping list quantity
	 *
	 * @return int value of shopping list quantity
	 **/
	public function getShoppingListQuantity(): int {
		return($this->shoppingListQuantity);
	}

	/**
	 * mutator method for shopping list quantity
	 *
	 * @param int $newShoppingListQuantity new value of shopping list item
	 * @throws \RangeException if $newShoppingListQuantity is negative
	 **/
	public function setShoppingListQuantity(int $newShoppingListQuantity): void {
		if($newShoppingListQuantity < 0) {
			throw(new \RangeException("shopping list quantity cannot be negative"));
		}

		$this->shoppingListQuantity = $newShoppingListQuantity;
	}

	/**
	 * inserts this Shopping List into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {
		// create query template
		$query = "INSERT INTO shoppingList(shoppingListId, shoppingListItem, shoppingListQuantity) VALUES(:shoppingListId, :shoppingListItem, :shoppingListQuantity)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["shoppingListId" => $this->shoppingListId->getBytes(), "shoppingListItem" => $this->shoppingListItem, "shoppingListQuantity" => $this->shoppingListQuantity];
		$statement->execute($parameters);
	}

	/**
	 * gets all Shopping Lists
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Shopping Lists found or empty array if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllShoppingLists(\PDO $pdo): \SplFixedArray {
		// create query template
		$query = "SELECT shoppingListId, shoppingListItem, shoppingListQuantity FROM shoppingList";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of shopping lists
		$shoppingLists = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$shoppingList = new ShoppingList($row["shoppingListId"], $row["shoppingListItem"], $row["shoppingListQuantity"]);
				$shoppingLists[$shoppingLists->key()] = $shoppingList;
				$shoppingLists->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($shoppingLists);
	}
}