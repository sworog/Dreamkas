package project.lighthouse.autotests.storage;

import project.lighthouse.autotests.guice.Injectors;
import project.lighthouse.autotests.storage.variable.OrderVariableStorage;

public class Storage {

    public static OrderVariableStorage getOrderVariableStorage() {
        return Injectors.getInjector().getInstance(OrderVariableStorage.class);
    }
}
