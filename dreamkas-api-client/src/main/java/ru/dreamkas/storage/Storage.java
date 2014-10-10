package ru.dreamkas.storage;

import ru.dreamkas.guice.Injectors;
import ru.dreamkas.storage.variable.CustomVariableStorage;
import ru.dreamkas.storage.variable.StockMovementVariableStorage;
import ru.dreamkas.storage.variable.UserVariableStorage;

public class Storage {

    public static UserVariableStorage getUserVariableStorage() {
        return Injectors.getInjector().getInstance(UserVariableStorage.class);
    }

    public static StockMovementVariableStorage getStockMovementVariableStorage() {
        return Injectors.getInjector().getInstance(StockMovementVariableStorage.class);
    }

    public static CustomVariableStorage getCustomVariableStorage() {
        return Injectors.getInjector().getInstance(CustomVariableStorage.class);
    }

    public static Configurable getConfigurationVariableStorage() {
        return Injectors.getInjector().getInstance(Configurable.class);
    }

    public static StorageClearable getStorageClearable() {
        return Injectors.getInjector().getInstance(StorageClearable.class);
    }
}
