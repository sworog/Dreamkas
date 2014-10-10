package ru.dreamkas.guice;

import com.google.inject.AbstractModule;
import com.google.inject.Singleton;
import ru.dreamkas.storage.Configurable;
import ru.dreamkas.storage.StorageClearable;
import ru.dreamkas.storage.variable.ConfigurationVariableStorage;
import ru.dreamkas.storage.variable.CustomVariableStorage;
import ru.dreamkas.storage.variable.StockMovementVariableStorage;
import ru.dreamkas.storage.variable.UserVariableStorage;

public class DreamkasModule extends AbstractModule {

    @Override
    protected void configure() {
        bind(UserVariableStorage.class).in(Singleton.class);
        bind(CustomVariableStorage.class).in(Singleton.class);
        bind(StockMovementVariableStorage.class).in(Singleton.class);
        bind(Configurable.class).to(ConfigurationVariableStorage.class).in(Singleton.class);
        bind(StorageClearable.class).to(CustomVariableStorage.class).in(Singleton.class);
    }
}
