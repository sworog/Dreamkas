package project.lighthouse.autotests;

import net.thucydides.core.pages.PageObject;

public interface CommonPageInterface {

    void isRequiredPageOpen(String pageObjectName);
    String GenerateTestData(int n);
    boolean isPresent(String xpath);
}
