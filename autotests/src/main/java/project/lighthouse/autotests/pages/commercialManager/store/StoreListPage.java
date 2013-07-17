package project.lighthouse.autotests.pages.commercialManager.store;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.elements.NonType;

@DefaultUrl("/stores")
public class StoreListPage extends CommonPageObject {

    CommonView commonView = new CommonView(getDriver());

    public StoreListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("number", new NonType(this, "number"));
        items.put("address", new NonType(this, "address"));
        items.put("contacts", new NonType(this, "contacts"));
    }

    public WebElement createNewStoreButton() {
        return findElement(By.xpath("//a[@class='button' and contains(text(), 'Новый магазин')]"));
    }

    public void checkStoreListItem(String columnName, String expectedValue) {
        By by = By.xpath(String.format("//span[@model_attr='%s' and contains(text(), '%s')]", columnName, expectedValue));
        findVisibleElement(by);
    }

    public WebElement findStoreRowInList(String storeNumber) {
        By by = By.xpath(String.format("//td/span[@model_attr='number' and contains(text(), '%s')]/..", storeNumber));
        return findVisibleElement(by);
    }
}
