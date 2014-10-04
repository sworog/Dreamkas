package project.lighthouse.autotests.common.item.interfaces;

import org.jbehave.core.model.ExamplesTable;

public interface Collectable extends CommonItemType{

    public void exactCompareExampleTable(ExamplesTable examplesTable);

    public void compareWithExampleTable(ExamplesTable examplesTable);

    public void clickByLocator(String locator);
}
