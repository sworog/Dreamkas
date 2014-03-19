package project.lighthouse.autotests.api.factories;

import org.json.JSONException;
import project.lighthouse.autotests.api.abstractFactory.AbstractFactory;
import project.lighthouse.autotests.objects.api.Order;
import project.lighthouse.autotests.objects.api.OrderProduct;

import java.io.IOException;

public class OrdersFactory extends AbstractFactory {

    public OrdersFactory(String userName, String password) {
        super(userName, password);
    }

    public Order createOrder(String supplierId, OrderProduct[] orderProducts, String storeId) throws JSONException, IOException {
        Order order = new Order(supplierId);
        order.setStoreId(storeId);
        for (OrderProduct orderProduct : orderProducts) {
            order.addProduct(orderProduct);
        }
        getHttpExecutor().executePostRequest(order);
        return order;
    }
}
