package ru.dreamkas.api;

import org.json.JSONException;
import org.json.JSONObject;
import ru.dreamkas.api.http.HttpExecutor;
import ru.dreamkas.api.objects.token.OauthAuthorizeData;
import ru.dreamkas.apihelper.UrlHelper;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apiStorage.Configurable;
import ru.dreamkas.apiStorage.variable.UserVariableStorage;

import java.io.IOException;

public class AccessToken {

    private final String userName;
    private final String password;

    public AccessToken(String userName, String password) {
        this.userName = userName;
        this.password = password;
    }

    public String get() {
        Configurable configuration = ApiStorage.getConfigurationVariableStorage();
        UserVariableStorage userVariableStorage = ApiStorage.getUserVariableStorage();

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
