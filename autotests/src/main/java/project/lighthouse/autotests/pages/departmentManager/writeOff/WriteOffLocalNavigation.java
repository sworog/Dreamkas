package project.lighthouse.autotests.pages.departmentManager.writeOff;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;

public class WriteOffLocalNavigation extends CommonPageObject {

    public WriteOffLocalNavigation(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void searchLinkClick() {
        new NavigationLinkFacade(this, "Поиск списания").click();
    }

    public void createInvoiceLinkClick() {
        new NavigationLinkFacade(this, "Создать списание").click();
    }
}
