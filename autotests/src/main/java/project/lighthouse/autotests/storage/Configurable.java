package project.lighthouse.autotests.storage;

public interface Configurable {

    public void setProperty(String name, String value);

    public void setTimeOutProperty(String name, Integer value);

    public String getProperty(String name);

    public Integer getTimeOutProperty(String name);

    public String getClientId();

    public String getClientSecret();
}
