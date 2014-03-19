package project.lighthouse.autotests.guice;

import com.google.inject.Guice;
import com.google.inject.Injector;

public class Injectors {

    private static Injector injector;

    public static synchronized Injector getInjector() {
        if (injector == null) {
            injector = Guice.createInjector(new LighthouseModule());
        }
        return injector;
    }
}
