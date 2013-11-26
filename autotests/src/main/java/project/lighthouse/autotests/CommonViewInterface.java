package project.lighthouse.autotests;

import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;

@Deprecated
public interface CommonViewInterface {

    void itemCheck(String value);

    void itemCheckIsNotPresent(String value);

    void itemClick(String value);

    @Deprecated
    void checkListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue);

    void checkListItemWithSkuHasExpectedValue(String value, ExamplesTable checkValuesTable);

    void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, By findBy, String expectedValue);

    void childrenItemClickByClassName(String elementName, String elementClassName);

    @Deprecated
    void childrentItemClickByFindByLocator(String elementName, By by);

    void childrenItemNavigateAndClickByFindByLocator(String elementName, By by);
}
