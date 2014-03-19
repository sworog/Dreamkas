package project.lighthouse.autotests.guice;

import com.google.inject.AbstractModule;
import com.google.inject.Singleton;
import project.lighthouse.autotests.storage.variable.OrderVariableStorage;

public class LighthouseModule extends AbstractModule {

    @Override
    protected void configure() {
        bind(OrderVariableStorage.class).in(Singleton.class);
    }
}
