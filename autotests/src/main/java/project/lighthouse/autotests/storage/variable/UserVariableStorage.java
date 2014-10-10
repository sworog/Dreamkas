package project.lighthouse.autotests.storage.variable;

import java.util.HashMap;
import java.util.Map;

public class UserVariableStorage {

    private Boolean isAuthorized = false;

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
}
