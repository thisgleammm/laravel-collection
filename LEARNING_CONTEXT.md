# Laravel Collection Learning Context

This repository contains exploration and tests for various Laravel Collection methods. This document serves as a reference context for applying these patterns in future projects.

## Covered Collection Methods

### 1. Basic Operations & CRUD
- **Creation**: `collect([1, 2, 3])`
- **Output**: `all()` to retrieve the underlying array.
- **Push/Pop**: `push($value)` to add to end, `pop()` to remove and get the last item.

### 2. Transformation (Mapping)
- **`map`**: Modifies each item in the collection.
- **`mapInto`**: Casts each item into a class instance (e.g., `Person::class`).
- **`mapSpread`**: Useful for array of arrays; spreads inner arrays into arguments for the callback.
- **`mapToGroups`**: Groups items by key/value pairs returned from the callback (returns collection of collections).
- **`flatMap`**: Maps and then flattens the result one level deep.

### 3. Combination & flattening
- **`zip`**: Merges items from two collections at corresponding indices.
- **`concat`**: Appends another collection's values to the end.
- **`combine`**: Uses one collection for keys and another for values to create an associative collection.
- **`collapse`**: Flattens a collection of arrays into a single flat collection.

### 4. string Operations
- **`join`**: Joins items into a string with a separator (e.g., `join("-")` or `join(", ", " and ")`).

### 5. Filtering & Partitioning
- **`filter`**: Returns items where the callback returns true.
- **`partition`**: Splits collection into two: one where the callback is true, and one where it is false.

### 6. Grouping
- **`groupBy`**: Groups items by a given key or callback return value.

### 7. Slicing & Chunking
- **`slice($offset, $length)`**: Returns a slice of the collection.
- **`take($n)`**: Takes the first `$n` items.
- **`takeUntil($callback)`**: Takes items until callback is true.
- **`takeWhile($callback)`**: Takes items while callback is true.
- **`skip($n)`**: Skips the first `$n` items.
- **`skipUntil($callback)`**: Skips items until callback is true.
- **`skipWhile($callback)`**: Skips items while callback is true.
- **`chunk($size)`**: Splits collection into chunks of the given size.

### 8. Retrieval
- **`first`**: Gets the first item (optional callback).
- **`last`**: Gets the last item (optional callback).
- **`random`**: Gets a random item.

### 9. Checking Existence
- **`isNotEmpty`** / **`isEmpty`**: Check if collection has items.
- **`contains`**: Checks if value exists or if callback returns true for any item.

### 10. Ordering
- **`sort`**: Sorts the collection (ascending).
- **`sortDesc`**: Sorts the collection (descending).

### 11. Aggregation
- **`sum`**: Sum of values.
- **`avg`**: Average of values.
- **`min`**: Minimum value.
- **`max`**: Maximum value.
- **`reduce`**: Reduces the collection to a single value using a callback.

### 12. Lazy Collections
- **`LazyCollection`**: Uses PHP Generators (yield) to handle large datasets efficiently without loading everything into memory.
  - Usage: `LazyCollection::make(function() { yield $val; })`.

## Usage Example

Review `tests/Feature/CollectionTest.php` for specific implementation details and assertions for each method.
