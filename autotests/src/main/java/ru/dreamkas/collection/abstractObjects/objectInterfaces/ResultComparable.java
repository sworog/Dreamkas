package ru.dreamkas.collection.abstractObjects.objectInterfaces;

import ru.dreamkas.collection.compare.CompareResults;

import java.util.Map;

/**
 * Interface for {@link ru.dreamkas.collection.abstractObjects.AbstractObject} to get compare result error
 */
public interface ResultComparable {

    public CompareResults getCompareResults(Map<String, String> row);
}
