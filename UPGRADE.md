# Upgrade guide

## 2.x to 3.x

* Minimum version was bumped from PHP 7.4 to PHP 8.0
* The signature of `PropertyMappingConverterInterface::convert(mixed $value): mixed` has been changed
* Methods in the `Map` class that refer to enums from the `dborsatto/smart-enums` library have been renamed to include the `smart` prefix so as to differentiate them from native enums
* `EnumPropertyMapping` and `EnumPropertiesMapping` have been renamed to `SmartEnumPropertyMapping` and `SmartEnumPropertiesMapping` respectively

## 1.x to 2.x

### `DBorsatto\SqlResultSetMapper\Map`

* `Map::root()` has been renamed to `Map::create()`
* `Map::relation()` was removed, use either `Map::singleRelation()` or `Map::multipleRelation` to make the kind of relation explicit.
* `Map::property()` no longer accept an optional conversion closure as third argument. To achieve the same behavior, use `Map::propertyConversion()`.

### Mapping objects

The inner workings of mapping objects have changed.

* `RootMapping` no longer exists. There is no longer a special class to be used as the entry point, everything that maps to a class will now use `ClassMapping`. This applies everywhere, from `Mapper`, to `Normalizer`, to `HydratorInterface`.
* `PropertyMapping` no longer provides a conversion closure. If you extended `PropertyMapping` with custom conversion closures, you will have to explicitly implement `PropertyMappingConverterInterface` and its method `convert`, which is where the content of the closure will need to be moved.
* For ease of use, `ClosurePropertyMapping` provides the same functionality as `PropertyMapping` did. It accepts a closure as third constructor parameter which will handle the value conversion.
* `RelationMapping` no longer returns the configuration values itself, but rather defines `RelationMapping::getClassMapping(): ClassMapping`. The constructor is now private, and creation must go through `single` or `multiple` to explicitly define the kind of relation.
