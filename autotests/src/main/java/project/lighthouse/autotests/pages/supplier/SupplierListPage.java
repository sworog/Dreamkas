package project.lighthouse.autotests.pages.supplier;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.BootstrapPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.objects.web.supplier.SupplierCollection;

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
    }

    public SupplierCollection getSupplierCollection() {
        return new SupplierCollection(getDriver());
    }
}
