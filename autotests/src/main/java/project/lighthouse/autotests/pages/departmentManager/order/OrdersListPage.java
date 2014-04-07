package project.lighthouse.autotests.pages.departmentManager.order;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.order.order.OrderObjectCollection;

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

    public OrderObjectCollection getOrderObjectCollection() {
        return new OrderObjectCollection(getDriver(), By.name("order"));
    }
}
