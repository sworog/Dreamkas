package ru.dreamkas.common.item.interfaces;

import org.jbehave.core.model.ExamplesTable;
import ru.dreamkas.collection.abstractObjects.AbstractObject;

public interface Collectable extends CommonItemType{

    public void exactCompareExampleTable(ExamplesTable examplesTable);

    public void compareWithExampleTable(ExamplesTable examplesTable);

    public void clickByLocator(String locator);

    public AbstractObject getAbstractObjectByLocator(String locator);

    public void contains(String locator);

    public void notContains(String locator);
}
