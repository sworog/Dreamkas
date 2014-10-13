package ru.dreamkas.pages.supplier;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.supplier.SupplierCollection;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;

@DefaultUrl("/suppliers")
public class SupplierListPage extends BootstrapPageObject {

    public SupplierListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        new PrimaryBtnFacade(this, "Добавить поставщика").click();
    }

    @Override
    public void createElements() {
        putDefaultCollection(new SupplierCollection(getDriver()));
    }
}
