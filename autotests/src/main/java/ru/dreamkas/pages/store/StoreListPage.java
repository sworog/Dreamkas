package ru.dreamkas.pages.store;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.store.StoreObjectCollection;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;

@DefaultUrl("/stores")
public class StoreListPage extends BootstrapPageObject {

    public StoreListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        new PrimaryBtnFacade(this, "Добавить магазин").click();
    }

    @Override
    public void createElements() {
        putDefaultCollection(new StoreObjectCollection(getDriver()));
    }
}
