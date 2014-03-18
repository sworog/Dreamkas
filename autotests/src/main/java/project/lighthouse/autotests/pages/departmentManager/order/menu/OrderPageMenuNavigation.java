package project.lighthouse.autotests.pages.departmentManager.order.menu;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;

/**
 * Page object class representing orders second navigation column
 */
public class OrderPageMenuNavigation extends CommonPageObject {

    public OrderPageMenuNavigation(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void createOrderLinkClick() {
        new NavigationLinkFacade(this, "Добавить заказ").click();
    }
}
