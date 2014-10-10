package project.lighthouse.autotests.guice;

import com.google.inject.AbstractModule;
import com.google.inject.Singleton;
import project.lighthouse.autotests.storage.Configurable;
import project.lighthouse.autotests.storage.CurrentPageObjectStorage;
import project.lighthouse.autotests.storage.DemoModeConfigurable;
import project.lighthouse.autotests.storage.variable.ConfigurationVariableStorage;
import project.lighthouse.autotests.storage.variable.CustomVariableStorage;
import project.lighthouse.autotests.storage.variable.UserVariableStorage;

public class LighthouseModule extends AbstractModule {

    @Override
    protected void configure() {
        bind(UserVariableStorage.class).in(Singleton.class);
        bind(CustomVariableStorage.class).in(Singleton.class);
        bind(CurrentPageObjectStorage.class).in(Singleton.class);
        bind(Configurable.class).to(ConfigurationVariableStorage.class).in(Singleton.class);
        bind(DemoModeConfigurable.class).to(ConfigurationVariableStorage.class).in(Singleton.class);
    }
}
