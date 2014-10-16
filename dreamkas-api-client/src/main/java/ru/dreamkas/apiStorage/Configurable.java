package ru.dreamkas.apiStorage;

public interface Configurable {

    public void setProperty(String name, String value);

    public String getProperty(String name);

    public String getClientId();

    public String getClientSecret();
}
