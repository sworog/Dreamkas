package ru.dreamkas.storage.variable;

import ru.dreamkas.storage.Configurable;

import java.util.HashMap;
import java.util.Map;

public class ConfigurationVariableStorage implements Configurable {

    private static final String CLIENT_ID = "autotests_autotests";
    private static final String CLIENT_SECRET = "secret";

    private Map<String, String> variables = new HashMap<>();

    public void setProperty(String name, String value) {
        variables.put(name, value);
    }

    public String getProperty(String name) {
        return variables.get(name);
    }

    public String getClientId() {
        return CLIENT_ID;
    }

    public String getClientSecret() {
        return CLIENT_SECRET;
    }
}
