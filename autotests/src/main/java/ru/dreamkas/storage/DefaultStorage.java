package ru.dreamkas.storage;

import ru.dreamkas.guice.Injectors;
import ru.dreamkas.storage.variable.CustomVariableStorage;
import ru.dreamkas.storage.variable.UserVariableStorage;

public class DefaultStorage {

    public static UserVariableStorage getUserVariableStorage() {
        return Injectors.getInjector().getInstance(UserVariableStorage.class);
    }

    public static CustomVariableStorage getCustomVariableStorage() {
        return Injectors.getInjector().getInstance(CustomVariableStorage.class);
    }

    public static CurrentPageObjectStorage getCurrentPageObjectStorage() {
        return Injectors.getInjector().getInstance(CurrentPageObjectStorage.class);
    }

    public static TimeOutConfigurable getTimeOutConfigurationVariableStorage() {
        return Injectors.getInjector().getInstance(TimeOutConfigurable.class);
    }

    public static DemoModeConfigurable getDemoModeConfigurableStorage() {
        return Injectors.getInjector().getInstance(DemoModeConfigurable.class);
    }
}
