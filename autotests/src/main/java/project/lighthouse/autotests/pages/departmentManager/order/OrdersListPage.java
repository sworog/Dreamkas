package project.lighthouse.autotests.pages.departmentManager.order;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

/**
 * Page object class representing orders list page
 */
@DefaultUrl("/orders")
public class OrdersListPage extends CommonPageObject {

    public OrdersListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }
}
