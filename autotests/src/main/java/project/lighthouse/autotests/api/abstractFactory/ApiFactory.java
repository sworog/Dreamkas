package project.lighthouse.autotests.api.abstractFactory;

import project.lighthouse.autotests.api.abstractFactory.factories.InvoicesFactory;
import project.lighthouse.autotests.api.abstractFactory.factories.OrdersFactory;

/**
 * Api factory to get api factories
 */
public class ApiFactory extends AbstractApiFactory {

    public ApiFactory(String userName, String password) {
        super(userName, password);
    }

    public InvoicesFactory getInvoicesFactory() {
        return (InvoicesFactory) this;
    }

    public OrdersFactory getOrdersFactory() {
        return (OrdersFactory) this;
    }
}
