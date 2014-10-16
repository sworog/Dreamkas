package ru.dreamkas.storage.variable;

import ru.dreamkas.storage.TimeOutConfigurable;
import ru.dreamkas.storage.DemoModeConfigurable;

import java.util.HashMap;
import java.util.Map;

public class ConfigurationVariableStorage implements TimeOutConfigurable, DemoModeConfigurable {

    private static final String CLIENT_ID = "autotests_autotests";
    private static final String CLIENT_SECRET = "secret";

    private Map<String, String> variables = new HashMap<>();
    private Map<String, Integer> timeOuts = new HashMap<>();

    private Boolean isDemoModeOn = false;
    private Boolean isDemoModePaused = true;

    public void setProperty(String name, String value) {
        variables.put(name, value);
    }

    public void setTimeOutProperty(String name, Integer value) {
        timeOuts.put(name, value);
    }

    public String getProperty(String name) {
        return variables.get(name);
    }

    public Integer getTimeOutProperty(String name) {
        return timeOuts.get(name);
    }

    public String getClientId() {
        return CLIENT_ID;
    }

    public String getClientSecret() {
        return CLIENT_SECRET;
    }

    public Boolean isDemoModeOn() {
        return isDemoModeOn;
    }

    public Boolean isDemoModePaused() {
        return isDemoModePaused;
    }

    public void setIsDemoModeOn(Boolean isDemoModeOn) {
        this.isDemoModeOn = isDemoModeOn;
    }

    public void setIsDemoModePaused(Boolean isDemoModePaused) {
        this.isDemoModePaused = isDemoModePaused;
    }
}
