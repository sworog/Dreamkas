package ru.dreamkas.storage;

public interface TimeOutConfigurable {

    public void setTimeOutProperty(String name, Integer value);

    public Integer getTimeOutProperty(String name);
}
