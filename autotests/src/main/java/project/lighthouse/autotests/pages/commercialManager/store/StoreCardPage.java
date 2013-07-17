package project.lighthouse.autotests.pages.commercialManager.store;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.Store;

public class StoreCardPage extends CommonPageObject {

    public StoreCardPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {

    }

    public void checkStoreCardValue(String fieldName, String value) {
        findModelFieldContaining(Store.NAME, fieldName, value);
    }

    public void checkStoreCardHeader(String header) {
        By by = By.xpath(String.format("//h3[contains(., '%s')]", header));
        findVisibleElement(by);
    }
}
