package project.lighthouse.autotests.storage;

import project.lighthouse.autotests.guice.Injectors;
import project.lighthouse.autotests.storage.variable.CustomVariableStorage;
import project.lighthouse.autotests.storage.variable.UserVariableStorage;

public class Storage {

    public static UserVariableStorage getUserVariableStorage() {
        return Injectors.getInjector().getInstance(UserVariableStorage.class);
    }

    public static CustomVariableStorage getCustomVariableStorage() {
        return Injectors.getInjector().getInstance(CustomVariableStorage.class);
    }

    public static CurrentPageObjectStorage getCurrentPageObjectStorage() {
        return Injectors.getInjector().getInstance(CurrentPageObjectStorage.class);
    }

    public static Configurable getConfigurationVariableStorage() {
        return Injectors.getInjector().getInstance(Configurable.class);
    }

    public static DemoModeConfigurable getDemoModeConfigurableStorage() {
        return Injectors.getInjector().getInstance(DemoModeConfigurable.class);
    }
}
