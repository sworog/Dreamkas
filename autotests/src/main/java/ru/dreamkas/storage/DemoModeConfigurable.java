package ru.dreamkas.storage;

public interface DemoModeConfigurable {

    public Boolean isDemoModeOn();

    public Boolean isDemoModePaused();

    public void setIsDemoModeOn(Boolean isDemoModeOn);

    public void setIsDemoModePaused(Boolean isDemoModePaused);
}
