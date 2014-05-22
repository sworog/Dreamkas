package project.lighthouse.autotests.guice;

import com.google.inject.AbstractModule;
import com.google.inject.Singleton;
import project.lighthouse.autotests.storage.variable.*;

public class LighthouseModule extends AbstractModule {

    @Override
    protected void configure() {
        bind(OrderVariableStorage.class).in(Singleton.class);
        bind(UserVariableStorage.class).in(Singleton.class);
        bind(StoreVariableStorage.class).in(Singleton.class);
        bind(InvoiceVariableStorage.class).in(Singleton.class);
        bind(CustomVariableStorage.class).in(Singleton.class);
    }
}
