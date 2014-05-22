package project.lighthouse.autotests.storage;

import project.lighthouse.autotests.guice.Injectors;
import project.lighthouse.autotests.storage.variable.*;

public class Storage {

    public static OrderVariableStorage getOrderVariableStorage() {
        return Injectors.getInjector().getInstance(OrderVariableStorage.class);
    }

    public static UserVariableStorage getUserVariableStorage() {
        return Injectors.getInjector().getInstance(UserVariableStorage.class);
    }

    public static StoreVariableStorage getStoreVariableStorage() {
        return Injectors.getInjector().getInstance(StoreVariableStorage.class);
    }

    public static InvoiceVariableStorage getInvoiceVariableStorage() {
        return Injectors.getInjector().getInstance(InvoiceVariableStorage.class);
    }

    public static CustomVariableStorage getCustomVariableStorage() {
        return Injectors.getInjector().getInstance(CustomVariableStorage.class);
    }
}
