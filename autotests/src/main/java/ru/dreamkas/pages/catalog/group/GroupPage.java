package ru.dreamkas.pages.catalog.group;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.product.ProductCollection;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.NonType;

/**
 * Group page object
 */
public class GroupPage extends BootstrapPageObject {

    public GroupPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        new PrimaryBtnFacade(this, "Добавить товар").click();
    }

    @Override
    public void createElements() {
        put("editGroupIcon", new NonType(this, By.xpath("//*[@class='fa fa-edit']")));
        put("longArrowBackLink", new NonType(this, By.xpath("//*[@class='fa fa-long-arrow-left']")));
        put("sortByName", new NonType(this, By.xpath("//*[@data-sort-by='name']")));
        put("sortBySellingPrice", new NonType(this, By.xpath("//*[@data-sort-by='name']")));
        put("sortByBarcode", new NonType(this, By.xpath("//*[@data-sort-by='name']")));
        putDefaultCollection(new ProductCollection(getDriver(), By.xpath("//tr[@data-modal='modal_product']")));
    }
}
