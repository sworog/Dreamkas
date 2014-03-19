package project.lighthouse.autotests.guice;

import com.google.inject.Injector;

public class Injectors extends net.thucydides.core.guice.Injectors {

    public static synchronized Injector getInjector() {
        return getInjector(new LighthouseModule());
    }
}
