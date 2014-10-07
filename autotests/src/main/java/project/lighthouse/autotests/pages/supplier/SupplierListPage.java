package project.lighthouse.autotests.pages.supplier;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.collection.supplier.SupplierCollection;
import project.lighthouse.autotests.common.pageObjects.BootstrapPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

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
