package project.lighthouse.autotests.common.item.interfaces;

import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;

public interface Collectable extends CommonItemType{

    public void exactCompareExampleTable(ExamplesTable examplesTable);

    public void compareWithExampleTable(ExamplesTable examplesTable);

    public void clickByLocator(String locator);

    public AbstractObject getAbstractObjectByLocator(String locator);

    public void contains(String locator);

    public void notContains(String locator);
}
