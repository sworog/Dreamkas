package project.lighthouse.autotests;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;

public interface CommonViewInterface {

    void itemCheck(String value);

    void itemClick(String value);

    void checkListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue);

    void checkListItemWithSkuHasExpectedValue(String value, ExamplesTable checkValuesTable);

    void childrenItemClickByClassName(String elementName, String elementClassName);

    void childrentItemClickByFindByLocator(String elementName, By by);
}
