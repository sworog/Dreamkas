package project.lighthouse.autotests.pages.departmentManager.invoice.menu;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;

public class InvoiceLocalNavigation extends CommonPageObject {

    public InvoiceLocalNavigation(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void searchLinkClick() {
        new NavigationLinkFacade(this, "Поиск накладной").click();
    }

    public void invoiceCreateLinkClick() {
        new NavigationLinkFacade(this, "Создать накладную").click();
    }
}
