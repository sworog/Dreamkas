package project.lighthouse.autotests.guice;

import com.google.inject.AbstractModule;
import com.google.inject.Singleton;
import project.lighthouse.autotests.storage.variable.OrderVariableStorage;
import project.lighthouse.autotests.storage.variable.StoreVariableStorage;
import project.lighthouse.autotests.storage.variable.UserVariableStorage;

public class LighthouseModule extends AbstractModule {

    @Override
    protected void configure() {
        bind(OrderVariableStorage.class).in(Singleton.class);
        bind(UserVariableStorage.class).in(Singleton.class);
        bind(StoreVariableStorage.class).in(Singleton.class);
    }
}
