package project.lighthouse.autotests.api;

import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.api.http.HttpExecutor;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.OauthAuthorizeData;
import project.lighthouse.autotests.storage.Configurable;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.variable.UserVariableStorage;

import java.io.IOException;

public class AccessToken {

    private final String userName;
    private final String password;

    public AccessToken(String userName, String password) {
        this.userName = userName;
        this.password = password;
    }

    public String get() {
        Configurable configuration = Storage.getConfigurationVariableStorage();
        UserVariableStorage userVariableStorage = Storage.getUserVariableStorage();

        if (!userVariableStorage.getUserTokens().containsKey(userName)) {
            String accessToken;
            try {
                String url = String.format("%s/oauth/v2/token", UrlHelper.getApiUrl());
                JSONObject jsonObject = new JSONObject()
                        .put("grant_type", "password")
                        .put("username", userName)
                        .put("password", password)
                        .put("client_id", configuration.getClientId())
                        .put("client_secret", configuration.getClientSecret());
                String response = HttpExecutor.getSimpleHttpRequestable().executeSimplePostRequest(url, jsonObject.toString());
                accessToken = new OauthAuthorizeData(new JSONObject(response)).getAccessToken();
            } catch (JSONException | IOException e) {
                throw new AssertionError(e.getMessage());
            }
            userVariableStorage.getUserTokens().put(userName, accessToken);
            return accessToken;
        } else {
            return userVariableStorage.getUserTokens().get(userName);
        }
    }
}
