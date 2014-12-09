package ru.dreamkas.apiGuice;

import com.google.inject.Guice;
import com.google.inject.Injector;

public class Injectors {

    private static Injector injector;

    private Injectors() {
        throw new UnsupportedOperationException();
    }

    public static synchronized Injector getInjector() {
        if (injector == null) {
            injector = Guice.createInjector(new DreamkasModule());
        }
        return injector;
    }
}
