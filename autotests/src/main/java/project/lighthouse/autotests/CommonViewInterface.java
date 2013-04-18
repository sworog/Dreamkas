package project.lighthouse.autotests;

import org.jbehave.core.model.ExamplesTable;

public interface CommonViewInterface {

    void itemCheck(String value);

    void itemClick(String value);

    void checkListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue);

    void checkListItemWithSkuHasExpectedValue(String value, ExamplesTable checkValuesTable);

    void childrenItemClick(String elementName, String elementClassName);
}
