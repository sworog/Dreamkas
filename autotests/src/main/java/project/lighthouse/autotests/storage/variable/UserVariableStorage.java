package project.lighthouse.autotests.storage.variable;

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
}
