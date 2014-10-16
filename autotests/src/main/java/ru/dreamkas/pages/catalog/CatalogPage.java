package ru.dreamkas.pages.catalog;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.catalog.GroupObjectCollection;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;

/**
 * Catalog page object
 */
@DefaultUrl("/catalog")
public class CatalogPage extends BootstrapPageObject {

    public CatalogPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        new PrimaryBtnFacade(this, "Добавить группу").click();
    }

    @Override
    public void createElements() {
        putDefaultCollection(new GroupObjectCollection(getDriver(), By.name("group")));
    }
}
