package ru.dreamkas.guice;

import com.google.inject.AbstractModule;
import com.google.inject.Singleton;
import ru.dreamkas.storage.TimeOutConfigurable;
import ru.dreamkas.storage.CurrentPageObjectStorage;
import ru.dreamkas.storage.DemoModeConfigurable;
import ru.dreamkas.storage.variable.ConfigurationVariableStorage;
import ru.dreamkas.storage.variable.CustomVariableStorage;
import ru.dreamkas.storage.variable.UserVariableStorage;

public class LighthouseModule extends AbstractModule {

    @Override
    protected void configure() {
        bind(UserVariableStorage.class).in(Singleton.class);
        bind(CustomVariableStorage.class).in(Singleton.class);
        bind(CurrentPageObjectStorage.class).in(Singleton.class);
        bind(TimeOutConfigurable.class).to(ConfigurationVariableStorage.class).in(Singleton.class);
        bind(DemoModeConfigurable.class).to(ConfigurationVariableStorage.class).in(Singleton.class);
    }
}
