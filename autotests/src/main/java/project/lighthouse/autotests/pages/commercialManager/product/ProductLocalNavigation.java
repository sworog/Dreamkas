package project.lighthouse.autotests.pages.commercialManager.product;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;

public class ProductLocalNavigation extends CommonPageObject {

    public ProductLocalNavigation(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void productInvoicesLinkClick() {
        new NavigationLinkFacade(this, "Приходы").click();
    }

    public void productWriteOffsLinkClick() {
        new NavigationLinkFacade(this, "Списания").click();
    }

    public void productReturnsLinkClick() {
        new NavigationLinkFacade(this, "Возвраты").click();
    }
}
