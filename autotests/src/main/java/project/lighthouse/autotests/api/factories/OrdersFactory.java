package project.lighthouse.autotests.api.factories;

import org.json.JSONException;
import project.lighthouse.autotests.objects.api.order.Order;
import project.lighthouse.autotests.objects.api.order.OrderProduct;

import java.io.IOException;

/**
 * Factory to create orders
 */
public class OrdersFactory extends ApiFactory {

    public OrdersFactory(String userName, String password) {
        super(userName, password);
    }

    public Order createOrder(String supplierId, OrderProduct[] orderProducts, String storeId) throws JSONException, IOException {
        Order order = new Order(supplierId);
        order.setStoreId(storeId);
        order.putProducts(orderProducts);
        createObject(order);
        return order;
    }
}
