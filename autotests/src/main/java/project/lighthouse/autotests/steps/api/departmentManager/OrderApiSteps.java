package project.lighthouse.autotests.steps.api.departmentManager;

import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.factories.OrdersFactory;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.Order;
import project.lighthouse.autotests.objects.api.OrderProduct;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.Supplier;

import java.io.IOException;

public class OrderApiSteps extends ScenarioSteps {

    private Order order;

    public Order createOrder(Supplier supplier, OrderProduct[] orderProducts, Store store, String userName, String password) throws IOException, JSONException {
        Order order = new OrdersFactory(userName, password).createOrder(supplier.getId(), orderProducts, store.getId());
        this.order = order;
        return order;
    }

    public void openOrderPage() throws JSONException {
        String url = String.format(
                "%s/stores/%s/orders/%s",
                UrlHelper.getWebFrontUrl(),
                order.getStoreId(),
                order.getId());
        getDriver().navigate().to(url);
    }
}
