package project.lighthouse.autotests;

public interface CommonViewInterface {

    void itemCheck(String value);
    void itemClick(String value);
    void checkListItemWithSkuHasExpectedValue(String value, String elementName, String expectedValue);
}
