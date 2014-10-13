package ru.dreamkas.apiStorage;

import ru.dreamkas.apiGuice.Injectors;
import ru.dreamkas.apiStorage.variable.CustomVariableStorage;
import ru.dreamkas.apiStorage.variable.StockMovementVariableStorage;
import ru.dreamkas.apiStorage.variable.UserVariableStorage;

public class ApiStorage {

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
