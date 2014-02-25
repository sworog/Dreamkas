package project.lighthouse.autotests.pages.commercialManager.supplier;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;

public class SupplierPageMenuNavigation extends CommonPageObject {

    public SupplierPageMenuNavigation(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void createSupplierLinkClick() {
        new NavigationLinkFacade(this, "Добавить поставщика").click();
    }
}
