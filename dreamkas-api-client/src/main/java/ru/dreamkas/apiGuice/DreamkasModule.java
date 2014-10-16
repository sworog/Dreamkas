package ru.dreamkas.apiGuice;

import com.google.inject.AbstractModule;
import com.google.inject.Singleton;
import ru.dreamkas.apiStorage.Configurable;
import ru.dreamkas.apiStorage.StorageClearable;
import ru.dreamkas.apiStorage.variable.ConfigurationVariableStorage;
import ru.dreamkas.apiStorage.variable.CustomVariableStorage;
import ru.dreamkas.apiStorage.variable.StockMovementVariableStorage;
import ru.dreamkas.apiStorage.variable.UserVariableStorage;

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
