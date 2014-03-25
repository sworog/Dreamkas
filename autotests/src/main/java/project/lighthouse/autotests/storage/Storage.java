package project.lighthouse.autotests.storage;

import project.lighthouse.autotests.guice.Injectors;
import project.lighthouse.autotests.storage.variable.OrderVariableStorage;
import project.lighthouse.autotests.storage.variable.UserVariableStorage;

public class Storage {

    public static OrderVariableStorage getOrderVariableStorage() {
        return Injectors.getInjector().getInstance(OrderVariableStorage.class);
    }

    public static UserVariableStorage getUserVariableStorage() {
        return Injectors.getInjector().getInstance(UserVariableStorage.class);
    }
}
