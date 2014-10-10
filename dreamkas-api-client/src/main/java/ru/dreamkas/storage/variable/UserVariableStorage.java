package ru.dreamkas.storage.variable;

import ru.dreamkas.storage.containers.user.UserContainerList;

import java.util.HashMap;
import java.util.Map;

public class UserVariableStorage {

    private Boolean isAuthorized = false;

    private String userProjectName;

    private Map<String, String> userTokens = new HashMap<>();

    public Map<String, String> getUserTokens() {
        return userTokens;
    }

    public Boolean getIsAuthorized() {
        return isAuthorized;
    }

    public void setIsAuthorized(Boolean isAuthorized) {
        this.isAuthorized = isAuthorized;
    }

    public String getUserProjectName() {
        return userProjectName;
    }

    public void setUserProjectName(String userProjectName) {
        this.userProjectName = userProjectName;
    }

    private UserContainerList userContainers = new UserContainerList();

    public UserContainerList getUserContainers() {
        return userContainers;
    }
}
