package project.lighthouse.autotests.pages.departmentManager.invoice;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;

public class InvoiceLocalNavigation extends CommonPageObject {

    public InvoiceLocalNavigation(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    public void searchLinkClick() {
        new NavigationLinkFacade(getDriver(), "Поиск накладной").click();
    }
}
