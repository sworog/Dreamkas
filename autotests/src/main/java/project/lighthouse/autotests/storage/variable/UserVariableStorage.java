package project.lighthouse.autotests.storage.variable;

import project.lighthouse.autotests.storage.containers.user.UserContainerList;

public class UserVariableStorage {

    private Boolean isAuthorized = false;

    private String userProjectName;

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
